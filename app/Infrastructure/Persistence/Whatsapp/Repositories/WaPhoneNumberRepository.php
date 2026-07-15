<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaPhoneNumber;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\WaPhoneNumberMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\WaPhoneNumberModel;

final class WaPhoneNumberRepository implements WaPhoneNumberRepositoryInterface
{
    public function __construct(private readonly WaPhoneNumberMapper $mapper)
    {
    }

    public function findByPhoneNumberId(string $phoneNumberId): ?WaPhoneNumber
    {
        $model = WaPhoneNumberModel::withoutGlobalScopes()
            ->where('phone_number_id', $phoneNumberId)
            ->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function findById(int $id): ?WaPhoneNumber
    {
        $model = WaPhoneNumberModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function listActive(): array
    {
        return WaPhoneNumberModel::where('is_active', true)
            ->get()
            ->map(fn (WaPhoneNumberModel $model) => $this->mapper->toDomain($model))
            ->all();
    }
}
