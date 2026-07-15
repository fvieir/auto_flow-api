<?php

declare(strict_types=1);

namespace App\Application\Service\UseCases;

use App\Domain\Service\Entities\Service;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;

final class ListServicesUseCase
{
    public function __construct(private readonly ServiceRepositoryInterface $services)
    {
    }

    /**
     * @return list<Service>
     */
    public function handle(): array
    {
        return $this->services->all();
    }
}
