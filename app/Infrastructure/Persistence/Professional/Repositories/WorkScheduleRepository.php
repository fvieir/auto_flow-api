<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Repositories;

use App\Domain\Professional\Entities\WorkSchedule;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;
use App\Infrastructure\Persistence\Professional\Mappers\WorkScheduleMapper;
use App\Infrastructure\Persistence\Professional\Models\WorkScheduleModel;

final class WorkScheduleRepository implements WorkScheduleRepositoryInterface
{
    public function __construct(private readonly WorkScheduleMapper $mapper)
    {
    }

    public function findById(int $id): ?WorkSchedule
    {
        $model = WorkScheduleModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function listByProfessional(int $professionalId): array
    {
        return WorkScheduleModel::where('professional_id', $professionalId)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get()
            ->map(fn (WorkScheduleModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(WorkSchedule $workSchedule): WorkSchedule
    {
        $model = WorkScheduleModel::create([
            'professional_id' => $workSchedule->professionalId(),
            'weekday' => $workSchedule->weekday(),
            'start_time' => $workSchedule->startTime(),
            'end_time' => $workSchedule->endTime(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function update(WorkSchedule $workSchedule): WorkSchedule
    {
        $model = WorkScheduleModel::findOrFail($workSchedule->id());

        $model->update([
            'weekday' => $workSchedule->weekday(),
            'start_time' => $workSchedule->startTime(),
            'end_time' => $workSchedule->endTime(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function delete(int $id): void
    {
        WorkScheduleModel::findOrFail($id)->delete();
    }
}
