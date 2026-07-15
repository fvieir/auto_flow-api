<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\UpdateProfessionalDTO;
use App\Domain\Professional\Entities\Professional;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class UpdateProfessionalUseCase
{
    public function __construct(private readonly ProfessionalRepositoryInterface $professionals)
    {
    }

    public function handle(int $id, UpdateProfessionalDTO $dto): Professional
    {
        $professional = $this->professionals->findById($id);

        if ($professional === null) {
            throw new ProfessionalNotFoundException($id);
        }

        $updated = $professional->withDetails($dto->name, $dto->phone, $dto->email);

        return $this->professionals->update($updated, $dto->serviceIds);
    }
}
