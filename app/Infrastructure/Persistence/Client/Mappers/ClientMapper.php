<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Client\Mappers;

use App\Domain\Client\Entities\Client;
use App\Infrastructure\Persistence\Client\Models\ClientModel;

final class ClientMapper
{
    public function toDomain(ClientModel $model): Client
    {
        return Client::fromPersistence(
            id: (int) $model->id,
            phone: $model->phone,
            name: $model->name,
            email: $model->email,
            document: $model->document,
        );
    }
}
