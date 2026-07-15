<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Application\Notification\UseCases\SendAppointmentReviewRequestUseCase;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class PostAppointmentReviewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public readonly int $appointmentId,
        public readonly int $tenantId,
    ) {
    }

    public function handle(
        AppointmentRepositoryInterface $appointments,
        SendAppointmentReviewRequestUseCase $sendReviewRequest,
        CurrentTenant $currentTenant,
    ): void {
        $currentTenant->set(new TenantId($this->tenantId));

        $appointment = $appointments->findById($this->appointmentId);

        if ($appointment === null) {
            return;
        }

        $sendReviewRequest->handle($appointment, $this->tenantId);
    }
}
