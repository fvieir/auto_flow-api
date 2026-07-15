<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Appointment\Support\TenantClock;
use App\Application\Notification\UseCases\SendAppointmentReminderUseCase;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class AppointmentReminder24hJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(
        TenantRepositoryInterface $tenants,
        AppointmentRepositoryInterface $appointments,
        SendAppointmentReminderUseCase $sendReminder,
        CurrentTenant $currentTenant,
    ): void {
        foreach ($tenants->listActive() as $tenant) {
            $currentTenant->set($tenant->id());

            $tenantNow = (new DateTimeImmutable())->setTimezone(new DateTimeZone($tenant->timezone()));
            $tomorrow = $tenantNow->modify('+1 day')->format('Y-m-d');
            $dayAfterTomorrow = $tenantNow->modify('+2 days')->format('Y-m-d');

            $windowStart = TenantClock::parseLocalDateTime("{$tomorrow} 00:00", $tenant->timezone());
            $windowEnd = TenantClock::parseLocalDateTime("{$dayAfterTomorrow} 00:00", $tenant->timezone());

            foreach ($appointments->dueForReminder($windowStart, $windowEnd) as $appointment) {
                $sendReminder->handle($appointment, $tenant->id()->value(), $tenant->timezone());
                $appointments->markReminderSent($appointment->id(), new DateTimeImmutable());
            }

            $currentTenant->clear();
        }
    }
}
