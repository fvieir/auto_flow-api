<?php

declare(strict_types=1);

namespace App\Application\Service\UseCases;

use App\Domain\Service\Exceptions\ServiceNotFoundException;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;

final class DeleteServiceUseCase
{
    public function __construct(private readonly ServiceRepositoryInterface $services)
    {
    }

    public function handle(int $id): void
    {
        if ($this->services->findById($id) === null) {
            throw new ServiceNotFoundException($id);
        }

        $this->services->delete($id);
    }
}
