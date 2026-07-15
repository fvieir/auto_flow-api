<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Service\Mappers;

use App\Domain\Service\Entities\Service;
use App\Infrastructure\Persistence\Service\Models\ServiceModel;

final class ServiceMapper
{
    public function toDomain(ServiceModel $model): Service
    {
        return Service::fromPersistence(
            id: (int) $model->id,
            name: $model->name,
            durationMinutes: (int) $model->duration_minutes,
            price: (float) $model->price,
        );
    }
}
