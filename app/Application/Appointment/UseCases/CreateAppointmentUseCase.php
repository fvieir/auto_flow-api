<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Application\Appointment\DTOs\CreateAppointmentDTO;
use App\Application\Appointment\Services\AvailabilityService;
use App\Application\Appointment\Support\TenantClock;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Exceptions\ServiceNotOfferedException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;
use App\Domain\Service\Exceptions\ServiceNotFoundException;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;

final class CreateAppointmentUseCase
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointments,
        private readonly ClientRepositoryInterface $clients,
        private readonly ProfessionalRepositoryInterface $professionals,
        private readonly ServiceRepositoryInterface $services,
        private readonly TenantRepositoryInterface $tenants,
        private readonly CurrentTenant $currentTenant,
        private readonly AvailabilityService $availability,
    ) {
    }

    public function handle(CreateAppointmentDTO $dto): Appointment
    {
        if ($this->clients->findById($dto->clientId) === null) {
            throw new ClientNotFoundException($dto->clientId);
        }

        if ($this->professionals->findById($dto->professionalId) === null) {
            throw new ProfessionalNotFoundException($dto->professionalId);
        }

        $service = $this->services->findById($dto->serviceId);

        if ($service === null) {
            throw new ServiceNotFoundException($dto->serviceId);
        }

        if (! $this->professionals->offersService($dto->professionalId, $dto->serviceId)) {
            throw new ServiceNotOfferedException();
        }

        $timezone = $this->tenants->findById($this->currentTenant->id())->timezone();
        $startsAt = TenantClock::parseLocalDateTime($dto->startsAt, $timezone);

        $appointment = Appointment::createNew(
            $dto->clientId,
            $dto->professionalId,
            $dto->serviceId,
            $startsAt,
            $service->durationMinutes(),
        );

        $this->availability->ensureSlotAvailable(
            $dto->professionalId,
            $appointment->startsAt(),
            $appointment->endsAt(),
            $timezone,
        );

        return $this->appointments->create($appointment);
    }
}
