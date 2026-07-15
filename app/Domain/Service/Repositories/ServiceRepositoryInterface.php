<?php

declare(strict_types=1);

namespace App\Domain\Service\Repositories;

use App\Domain\Service\Entities\Service;

interface ServiceRepositoryInterface
{
    public function findById(int $id): ?Service;

    /**
     * @return list<Service>
     */
    public function all(): array;

    public function create(Service $service): Service;

    public function update(Service $service): Service;

    public function delete(int $id): void;
}
