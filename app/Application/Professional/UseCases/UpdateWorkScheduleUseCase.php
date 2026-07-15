<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\UpdateWorkScheduleDTO;
use App\Domain\Professional\Entities\WorkSchedule;
use App\Domain\Professional\Exceptions\WorkScheduleNotFoundException;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;

final class UpdateWorkScheduleUseCase
{
    public function __construct(private readonly WorkScheduleRepositoryInterface $workSchedules)
    {
    }

    public function handle(int $professionalId, int $id, UpdateWorkScheduleDTO $dto): WorkSchedule
    {
        $workSchedule = $this->workSchedules->findById($id);

        if ($workSchedule === null || $workSchedule->professionalId() !== $professionalId) {
            throw new WorkScheduleNotFoundException($id);
        }

        $updated = $workSchedule->withDetails($dto->weekday, $dto->startTime, $dto->endTime);

        return $this->workSchedules->update($updated);
    }
}
