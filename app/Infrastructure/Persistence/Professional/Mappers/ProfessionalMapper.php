<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Mappers;

use App\Domain\Professional\Entities\Professional;
use App\Infrastructure\Persistence\Professional\Models\ProfessionalModel;

final class ProfessionalMapper
{
    public function toDomain(ProfessionalModel $model): Professional
    {
        return Professional::fromPersistence(
            id: (int) $model->id,
            name: $model->name,
            phone: $model->phone,
            email: $model->email,
        );
    }
}
