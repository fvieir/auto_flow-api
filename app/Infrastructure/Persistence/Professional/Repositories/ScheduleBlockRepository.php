<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Repositories;

use App\Domain\Professional\Entities\ScheduleBlock;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;
use App\Infrastructure\Persistence\Professional\Mappers\ScheduleBlockMapper;
use App\Infrastructure\Persistence\Professional\Models\ScheduleBlockModel;

final class ScheduleBlockRepository implements ScheduleBlockRepositoryInterface
{
    public function __construct(private readonly ScheduleBlockMapper $mapper)
    {
    }

    public function findById(int $id): ?ScheduleBlock
    {
        $model = ScheduleBlockModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function listByProfessional(int $professionalId): array
    {
        return ScheduleBlockModel::where('professional_id', $professionalId)
            ->orderBy('start_at')
            ->get()
            ->map(fn (ScheduleBlockModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(ScheduleBlock $scheduleBlock): ScheduleBlock
    {
        $model = ScheduleBlockModel::create([
            'professional_id' => $scheduleBlock->professionalId(),
            'start_at' => $scheduleBlock->startAt(),
            'end_at' => $scheduleBlock->endAt(),
            'reason' => $scheduleBlock->reason(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function update(ScheduleBlock $scheduleBlock): ScheduleBlock
    {
        $model = ScheduleBlockModel::findOrFail($scheduleBlock->id());

        $model->update([
            'start_at' => $scheduleBlock->startAt(),
            'end_at' => $scheduleBlock->endAt(),
            'reason' => $scheduleBlock->reason(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function delete(int $id): void
    {
        ScheduleBlockModel::findOrFail($id)->delete();
    }
}
