<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Application\Client\DTOs\CreateAddressDTO;
use App\Domain\Client\Entities\ClientAddress;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;

final class CreateAddressUseCase
{
    public function __construct(
        private readonly ClientAddressRepositoryInterface $addresses,
        private readonly ClientRepositoryInterface $clients,
    ) {
    }

    public function handle(int $clientId, CreateAddressDTO $dto): ClientAddress
    {
        if ($this->clients->findById($clientId) === null) {
            throw new ClientNotFoundException($clientId);
        }

        $address = ClientAddress::createNew(
            clientId: $clientId,
            postalCode: $dto->postalCode,
            street: $dto->street,
            number: $dto->number,
            complement: $dto->complement,
            neighborhood: $dto->neighborhood,
            city: $dto->city,
            state: $dto->state,
            isPrimary: $dto->isPrimary,
        );

        return $this->addresses->create($address);
    }
}
