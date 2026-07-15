<?php

declare(strict_types=1);

namespace App\Domain\Professional\Repositories;

use App\Domain\Professional\Entities\WorkSchedule;

interface WorkScheduleRepositoryInterface
{
    public function findById(int $id): ?WorkSchedule;

    /**
     * @return list<WorkSchedule>
     */
    public function listByProfessional(int $professionalId): array;

    public function create(WorkSchedule $workSchedule): WorkSchedule;

    public function update(WorkSchedule $workSchedule): WorkSchedule;

    public function delete(int $id): void;
}
