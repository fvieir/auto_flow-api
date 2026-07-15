<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Application\Client\DTOs\UpdateAddressDTO;
use App\Domain\Client\Entities\ClientAddress;
use App\Domain\Client\Exceptions\ClientAddressNotFoundException;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;

final class UpdateAddressUseCase
{
    public function __construct(private readonly ClientAddressRepositoryInterface $addresses)
    {
    }

    public function handle(int $clientId, int $id, UpdateAddressDTO $dto): ClientAddress
    {
        $address = $this->addresses->findById($id);

        if ($address === null || $address->clientId() !== $clientId) {
            throw new ClientAddressNotFoundException($id);
        }

        $updated = $address->withDetails(
            postalCode: $dto->postalCode,
            street: $dto->street,
            number: $dto->number,
            complement: $dto->complement,
            neighborhood: $dto->neighborhood,
            city: $dto->city,
            state: $dto->state,
            isPrimary: $dto->isPrimary,
        );

        return $this->addresses->update($updated);
    }
}
