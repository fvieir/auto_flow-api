<?php

declare(strict_types=1);

namespace App\Domain\Client\Repositories;

use App\Domain\Client\Entities\Client;

interface ClientRepositoryInterface
{
    public function findById(int $id): ?Client;

    public function findByPhone(string $phone): ?Client;

    /**
     * @return list<Client>
     */
    public function all(): array;

    /**
     * @return list<Client>
     */
    public function search(string $query): array;

    public function create(Client $client): Client;

    public function update(Client $client): Client;

    public function delete(int $id): void;
}
