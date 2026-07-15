<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\CreateWorkScheduleDTO;
use App\Domain\Professional\Entities\WorkSchedule;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class CreateWorkScheduleUseCase
{
    public function __construct(
        private readonly WorkScheduleRepositoryInterface $workSchedules,
        private readonly ProfessionalRepositoryInterface $professionals,
    ) {
    }

    public function handle(int $professionalId, CreateWorkScheduleDTO $dto): WorkSchedule
    {
        if ($this->professionals->findById($professionalId) === null) {
            throw new ProfessionalNotFoundException($professionalId);
        }

        $workSchedule = WorkSchedule::createNew($professionalId, $dto->weekday, $dto->startTime, $dto->endTime);

        return $this->workSchedules->create($workSchedule);
    }
}
