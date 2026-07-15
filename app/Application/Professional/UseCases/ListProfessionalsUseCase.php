<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Entities\Professional;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class ListProfessionalsUseCase
{
    public function __construct(private readonly ProfessionalRepositoryInterface $professionals)
    {
    }

    /**
     * @return list<Professional>
     */
    public function handle(): array
    {
        return $this->professionals->all();
    }
}
