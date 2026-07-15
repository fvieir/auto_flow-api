<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Mappers;

use App\Domain\Professional\Entities\WorkSchedule;
use App\Infrastructure\Persistence\Professional\Models\WorkScheduleModel;

final class WorkScheduleMapper
{
    public function toDomain(WorkScheduleModel $model): WorkSchedule
    {
        return WorkSchedule::fromPersistence(
            id: (int) $model->id,
            professionalId: (int) $model->professional_id,
            weekday: (int) $model->weekday,
            startTime: substr((string) $model->start_time, 0, 5),
            endTime: substr((string) $model->end_time, 0, 5),
        );
    }
}
