<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Client\Mappers;

use App\Domain\Client\Entities\ClientAddress;
use App\Infrastructure\Persistence\Client\Models\ClientAddressModel;

final class ClientAddressMapper
{
    public function toDomain(ClientAddressModel $model): ClientAddress
    {
        return ClientAddress::fromPersistence(
            id: (int) $model->id,
            clientId: (int) $model->client_id,
            postalCode: $model->postal_code,
            street: $model->street,
            number: $model->number,
            complement: $model->complement,
            neighborhood: $model->neighborhood,
            city: $model->city,
            state: $model->state,
            isPrimary: (bool) $model->is_primary,
        );
    }
}
