<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Application\Client\DTOs\UpdateClientDTO;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

final class UpdateClientUseCase
{
    public function __construct(private readonly ClientRepositoryInterface $clients)
    {
    }

    public function handle(int $id, UpdateClientDTO $dto): Client
    {
        $client = $this->clients->findById($id);

        if ($client === null) {
            throw new ClientNotFoundException($id);
        }

        $updated = $client->withDetails(
            phone: $dto->phone ?? $client->phone(),
            name: $dto->name ?? $client->name(),
            email: $dto->email ?? $client->email(),
            document: $dto->document ?? $client->document(),
        );

        return $this->clients->update($updated);
    }
}
