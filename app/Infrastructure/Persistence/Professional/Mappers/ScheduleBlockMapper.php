<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Mappers;

use App\Domain\Professional\Entities\ScheduleBlock;
use App\Infrastructure\Persistence\Professional\Models\ScheduleBlockModel;

final class ScheduleBlockMapper
{
    public function toDomain(ScheduleBlockModel $model): ScheduleBlock
    {
        return ScheduleBlock::fromPersistence(
            id: (int) $model->id,
            professionalId: (int) $model->professional_id,
            startAt: $model->start_at->toDateTimeImmutable(),
            endAt: $model->end_at->toDateTimeImmutable(),
            reason: $model->reason,
        );
    }
}
