<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class DeleteProfessionalUseCase
{
    public function __construct(private readonly ProfessionalRepositoryInterface $professionals)
    {
    }

    public function handle(int $id): void
    {
        if ($this->professionals->findById($id) === null) {
            throw new ProfessionalNotFoundException($id);
        }

        $this->professionals->delete($id);
    }
}
