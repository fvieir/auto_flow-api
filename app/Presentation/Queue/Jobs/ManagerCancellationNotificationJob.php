<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Appointment\Support\TenantClock;
use App\Application\Notification\UseCases\NotifyManagerUseCase;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class ManagerCancellationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public readonly int $appointmentId,
        public readonly int $tenantId,
    ) {
    }

    public function handle(
        AppointmentRepositoryInterface $appointments,
        ClientRepositoryInterface $clients,
        TenantRepositoryInterface $tenants,
        NotifyManagerUseCase $notifyManager,
        CurrentTenant $currentTenant,
    ): void {
        $currentTenant->set(new TenantId($this->tenantId));

        $appointment = $appointments->findById($this->appointmentId);

        if ($appointment === null) {
            return;
        }

        $client = $clients->findById($appointment->clientId());
        $tenant = $tenants->findById(new TenantId($this->tenantId));

        if ($tenant === null) {
            return;
        }

        $localDateTime = TenantClock::formatLocal($appointment->startsAt(), $tenant->timezone());

        $text = sprintf(
            'Agendamento cancelado: %s às %s (cliente: %s).',
            substr($localDateTime, 0, 10),
            substr($localDateTime, 11, 5),
            $client?->name() ?? $client?->phone() ?? 'desconhecido',
        );

        $notifyManager->handle($this->tenantId, $text);
    }
}
