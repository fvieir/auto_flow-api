<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Client\Repositories;

use App\Domain\Client\Entities\ClientAddress;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;
use App\Infrastructure\Persistence\Client\Mappers\ClientAddressMapper;
use App\Infrastructure\Persistence\Client\Models\ClientAddressModel;
use Illuminate\Support\Facades\DB;

final class ClientAddressRepository implements ClientAddressRepositoryInterface
{
    public function __construct(private readonly ClientAddressMapper $mapper)
    {
    }

    public function findById(int $id): ?ClientAddress
    {
        $model = ClientAddressModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function listByClient(int $clientId): array
    {
        return ClientAddressModel::where('client_id', $clientId)
            ->orderByDesc('is_primary')
            ->get()
            ->map(fn (ClientAddressModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(ClientAddress $address): ClientAddress
    {
        return DB::transaction(function () use ($address): ClientAddress {
            if ($address->isPrimary()) {
                $this->unsetPrimaryFor($address->clientId());
            }

            $model = ClientAddressModel::create([
                'client_id' => $address->clientId(),
                'postal_code' => $address->postalCode(),
                'street' => $address->street(),
                'number' => $address->number(),
                'complement' => $address->complement(),
                'neighborhood' => $address->neighborhood(),
                'city' => $address->city(),
                'state' => $address->state(),
                'is_primary' => $address->isPrimary(),
            ]);

            return $this->mapper->toDomain($model);
        });
    }

    public function update(ClientAddress $address): ClientAddress
    {
        return DB::transaction(function () use ($address): ClientAddress {
            if ($address->isPrimary()) {
                $this->unsetPrimaryFor($address->clientId(), except: $address->id());
            }

            $model = ClientAddressModel::findOrFail($address->id());

            $model->update([
                'postal_code' => $address->postalCode(),
                'street' => $address->street(),
                'number' => $address->number(),
                'complement' => $address->complement(),
                'neighborhood' => $address->neighborhood(),
                'city' => $address->city(),
                'state' => $address->state(),
                'is_primary' => $address->isPrimary(),
            ]);

            return $this->mapper->toDomain($model);
        });
    }

    public function delete(int $id): void
    {
        ClientAddressModel::findOrFail($id)->delete();
    }

    public function setPrimary(int $addressId, int $clientId): ClientAddress
    {
        return DB::transaction(function () use ($addressId, $clientId): ClientAddress {
            $this->unsetPrimaryFor($clientId, except: $addressId);

            $model = ClientAddressModel::findOrFail($addressId);
            $model->update(['is_primary' => true]);

            return $this->mapper->toDomain($model);
        });
    }

    private function unsetPrimaryFor(int $clientId, ?int $except = null): void
    {
        ClientAddressModel::where('client_id', $clientId)
            ->where('is_primary', true)
            ->when($except !== null, fn ($query) => $query->where('id', '!=', $except))
            ->update(['is_primary' => false]);
    }
}
