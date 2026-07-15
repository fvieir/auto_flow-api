<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Client\Repositories;

use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Infrastructure\Persistence\Client\Mappers\ClientMapper;
use App\Infrastructure\Persistence\Client\Models\ClientModel;

final class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(private readonly ClientMapper $mapper)
    {
    }

    public function findById(int $id): ?Client
    {
        $model = ClientModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function findByPhone(string $phone): ?Client
    {
        $model = ClientModel::where('phone', $phone)->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function all(): array
    {
        return ClientModel::orderBy('name')
            ->get()
            ->map(fn (ClientModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function search(string $query): array
    {
        return ClientModel::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('document', 'like', "%{$query}%")
            ->orderBy('name')
            ->get()
            ->map(fn (ClientModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(Client $client): Client
    {
        $model = ClientModel::create([
            'phone' => $client->phone(),
            'name' => $client->name(),
            'email' => $client->email(),
            'document' => $client->document(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function update(Client $client): Client
    {
        $model = ClientModel::findOrFail($client->id());

        $model->update([
            'phone' => $client->phone(),
            'name' => $client->name(),
            'email' => $client->email(),
            'document' => $client->document(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function delete(int $id): void
    {
        ClientModel::findOrFail($id)->delete();
    }
}
