<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Appointment\Support\TenantClock;
use App\Application\Notification\UseCases\NotifyManagerUseCase;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Enums\AppointmentStatus;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

final class ManagerMorningSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(
        TenantRepositoryInterface $tenants,
        AppointmentRepositoryInterface $appointments,
        ClientRepositoryInterface $clients,
        NotifyManagerUseCase $notifyManager,
        CurrentTenant $currentTenant,
    ): void {
        $targetHour = (int) config('services.notifications.manager_summary_hour');

        foreach ($tenants->listActive() as $tenant) {
            $tenantNow = (new DateTimeImmutable())->setTimezone(new DateTimeZone($tenant->timezone()));

            if ((int) $tenantNow->format('H') !== $targetHour) {
                continue;
            }

            $today = $tenantNow->format('Y-m-d');
            $dedupeKey = "manager-summary-sent:{$tenant->id()->value()}:{$today}";

            if (! Redis::set($dedupeKey, '1', 'EX', 26 * 3600, 'NX')) {
                continue;
            }

            $currentTenant->set($tenant->id());

            $windowStart = TenantClock::parseLocalDateTime("{$today} 00:00", $tenant->timezone());
            $tomorrow = $tenantNow->modify('+1 day')->format('Y-m-d');
            $windowEnd = TenantClock::parseLocalDateTime("{$tomorrow} 00:00", $tenant->timezone());

            $todaysAppointments = array_values(array_filter(
                $appointments->list(startDate: $windowStart, endDate: $windowEnd),
                fn ($appointment) => $appointment->status() !== AppointmentStatus::Cancelled,
            ));

            $notifyManager->handle($tenant->id()->value(), $this->buildSummary($todaysAppointments, $clients, $tenant->timezone()));

            $currentTenant->clear();
        }
    }

    /**
     * @param  list<Appointment>  $todaysAppointments
     */
    private function buildSummary(array $todaysAppointments, ClientRepositoryInterface $clients, string $timezone): string
    {
        if ($todaysAppointments === []) {
            return 'Bom dia! Você não tem agendamentos para hoje.';
        }

        $lines = array_map(function ($appointment) use ($clients, $timezone) {
            $client = $clients->findById($appointment->clientId());
            $time = substr(TenantClock::formatLocal($appointment->startsAt(), $timezone), 11, 5);
            $clientLabel = $client?->name() ?? $client?->phone() ?? 'cliente';

            return "- {$time}: {$clientLabel}";
        }, $todaysAppointments);

        $count = count($todaysAppointments);

        return sprintf("Bom dia! Você tem %d agendamento(s) hoje:\n%s", $count, implode("\n", $lines));
    }
}
