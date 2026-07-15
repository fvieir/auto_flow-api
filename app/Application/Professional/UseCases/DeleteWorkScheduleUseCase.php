<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Exceptions\WorkScheduleNotFoundException;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;

final class DeleteWorkScheduleUseCase
{
    public function __construct(private readonly WorkScheduleRepositoryInterface $workSchedules)
    {
    }

    public function handle(int $professionalId, int $id): void
    {
        $workSchedule = $this->workSchedules->findById($id);

        if ($workSchedule === null || $workSchedule->professionalId() !== $professionalId) {
            throw new WorkScheduleNotFoundException($id);
        }

        $this->workSchedules->delete($id);
    }
}
