<?php

declare(strict_types=1);

namespace App\Application\Service\UseCases;

use App\Application\Service\DTOs\CreateServiceDTO;
use App\Domain\Service\Entities\Service;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;

final class CreateServiceUseCase
{
    public function __construct(private readonly ServiceRepositoryInterface $services)
    {
    }

    public function handle(CreateServiceDTO $dto): Service
    {
        $service = Service::createNew($dto->name, $dto->durationMinutes, $dto->price);

        return $this->services->create($service);
    }
}
