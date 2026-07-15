<?php

declare(strict_types=1);

namespace App\Application\Appointment\UseCases;

use App\Application\Appointment\Services\AvailabilityService;
use App\Application\Appointment\Support\TenantClock;
use App\Domain\Appointment\Exceptions\ServiceNotOfferedException;
use App\Domain\Professional\Entities\Professional;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;
use App\Domain\Service\Exceptions\ServiceNotFoundException;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;

final class ListAvailabilityUseCase
{
    public function __construct(
        private readonly ServiceRepositoryInterface $services,
        private readonly ProfessionalRepositoryInterface $professionals,
        private readonly TenantRepositoryInterface $tenants,
        private readonly CurrentTenant $currentTenant,
        private readonly AvailabilityService $availability,
    ) {
    }

    /**
     * @return list<array{professional_id: int, professional_name: string, slots: list<array{start: string, end: string}>}>
     */
    public function handle(int $serviceId, string $data, ?int $professionalId): array
    {
        $service = $this->services->findById($serviceId);

        if ($service === null) {
            throw new ServiceNotFoundException($serviceId);
        }

        if ($professionalId !== null) {
            $professional = $this->professionals->findById($professionalId);

            if ($professional === null) {
                throw new ProfessionalNotFoundException($professionalId);
            }

            if (! $this->professionals->offersService($professionalId, $serviceId)) {
                throw new ServiceNotOfferedException();
            }

            $professionalList = [$professional];
        } else {
            $professionalList = $this->professionals->listByService($serviceId);
        }

        $timezone = $this->tenants->findById($this->currentTenant->id())->timezone();
        $localDay = TenantClock::parseLocalDate($data, $timezone);

        return array_map(
            fn (Professional $professional) => [
                'professional_id' => $professional->id(),
                'professional_name' => $professional->name(),
                'slots' => array_map(
                    fn (array $slot) => [
                        'start' => TenantClock::formatLocal($slot['start'], $timezone),
                        'end' => TenantClock::formatLocal($slot['end'], $timezone),
                    ],
                    $this->availability->listFreeSlots(
                        $professional->id(),
                        $localDay,
                        $service->durationMinutes(),
                        $timezone,
                    ),
                ),
            ],
            $professionalList,
        );
    }
}
