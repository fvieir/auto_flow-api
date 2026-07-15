<?php

declare(strict_types=1);

namespace App\Domain\Professional\Repositories;

use App\Domain\Professional\Entities\Professional;

interface ProfessionalRepositoryInterface
{
    public function findById(int $id): ?Professional;

    /**
     * @return list<Professional>
     */
    public function all(): array;

    /**
     * @return list<Professional>
     */
    public function listByService(int $serviceId): array;

    public function offersService(int $professionalId, int $serviceId): bool;

    /**
     * @param  list<int>  $serviceIds
     */
    public function create(Professional $professional, array $serviceIds): Professional;

    /**
     * @param  list<int>  $serviceIds
     */
    public function update(Professional $professional, array $serviceIds): Professional;

    public function delete(int $id): void;
}
