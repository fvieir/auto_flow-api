<?php

declare(strict_types=1);

namespace App\Application\Service\UseCases;

use App\Application\Service\DTOs\UpdateServiceDTO;
use App\Domain\Service\Entities\Service;
use App\Domain\Service\Exceptions\ServiceNotFoundException;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;

final class UpdateServiceUseCase
{
    public function __construct(private readonly ServiceRepositoryInterface $services)
    {
    }

    public function handle(int $id, UpdateServiceDTO $dto): Service
    {
        $service = $this->services->findById($id);

        if ($service === null) {
            throw new ServiceNotFoundException($id);
        }

        $updated = $service->withDetails($dto->name, $dto->durationMinutes, $dto->price);

        return $this->services->update($updated);
    }
}
