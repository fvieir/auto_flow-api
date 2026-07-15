<?php

declare(strict_types=1);

namespace App\Domain\Professional\Repositories;

use App\Domain\Professional\Entities\ScheduleBlock;

interface ScheduleBlockRepositoryInterface
{
    public function findById(int $id): ?ScheduleBlock;

    /**
     * @return list<ScheduleBlock>
     */
    public function listByProfessional(int $professionalId): array;

    public function create(ScheduleBlock $scheduleBlock): ScheduleBlock;

    public function update(ScheduleBlock $scheduleBlock): ScheduleBlock;

    public function delete(int $id): void;
}
