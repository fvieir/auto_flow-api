<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Application\Client\DTOs\CreateClientDTO;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

final class CreateClientUseCase
{
    public function __construct(private readonly ClientRepositoryInterface $clients)
    {
    }

    public function handle(CreateClientDTO $dto): Client
    {
        $client = Client::createNew($dto->phone, $dto->name, $dto->email, $dto->document);

        return $this->clients->create($client);
    }
}
