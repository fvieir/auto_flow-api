<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Client\Entities\ClientAddress;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;

final class ListAddressesUseCase
{
    public function __construct(
        private readonly ClientAddressRepositoryInterface $addresses,
        private readonly ClientRepositoryInterface $clients,
    ) {
    }

    /**
     * @return list<ClientAddress>
     */
    public function handle(int $clientId): array
    {
        if ($this->clients->findById($clientId) === null) {
            throw new ClientNotFoundException($clientId);
        }

        return $this->addresses->listByClient($clientId);
    }
}
