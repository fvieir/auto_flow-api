<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

final class ListClientsUseCase
{
    public function __construct(private readonly ClientRepositoryInterface $clients)
    {
    }

    /**
     * @return list<Client>
     */
    public function handle(): array
    {
        return $this->clients->all();
    }
}
