<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Entities\WorkSchedule;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class ListWorkSchedulesUseCase
{
    public function __construct(
        private readonly WorkScheduleRepositoryInterface $workSchedules,
        private readonly ProfessionalRepositoryInterface $professionals,
    ) {
    }

    /**
     * @return list<WorkSchedule>
     */
    public function handle(int $professionalId): array
    {
        if ($this->professionals->findById($professionalId) === null) {
            throw new ProfessionalNotFoundException($professionalId);
        }

        return $this->workSchedules->listByProfessional($professionalId);
    }
}
