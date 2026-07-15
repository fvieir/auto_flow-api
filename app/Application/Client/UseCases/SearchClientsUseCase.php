<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

final class SearchClientsUseCase
{
    public function __construct(private readonly ClientRepositoryInterface $clients)
    {
    }

    /**
     * @return list<Client>
     */
    public function handle(string $query): array
    {
        return $this->clients->search($query);
    }
}
