<?php

declare(strict_types=1);

namespace App\Domain\Client\Repositories;

use App\Domain\Client\Entities\ClientAddress;

interface ClientAddressRepositoryInterface
{
    public function findById(int $id): ?ClientAddress;

    /**
     * @return list<ClientAddress>
     */
    public function listByClient(int $clientId): array;

    public function create(ClientAddress $address): ClientAddress;

    public function update(ClientAddress $address): ClientAddress;

    public function delete(int $id): void;

    public function setPrimary(int $addressId, int $clientId): ClientAddress;
}
