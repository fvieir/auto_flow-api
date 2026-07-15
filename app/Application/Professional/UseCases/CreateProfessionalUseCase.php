<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\CreateProfessionalDTO;
use App\Domain\Professional\Entities\Professional;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class CreateProfessionalUseCase
{
    public function __construct(private readonly ProfessionalRepositoryInterface $professionals)
    {
    }

    public function handle(CreateProfessionalDTO $dto): Professional
    {
        $professional = Professional::createNew($dto->name, $dto->phone, $dto->email);

        return $this->professionals->create($professional, $dto->serviceIds);
    }
}
