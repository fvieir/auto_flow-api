<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Entities\ScheduleBlock;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class ListScheduleBlocksUseCase
{
    public function __construct(
        private readonly ScheduleBlockRepositoryInterface $scheduleBlocks,
        private readonly ProfessionalRepositoryInterface $professionals,
    ) {
    }

    /**
     * @return list<ScheduleBlock>
     */
    public function handle(int $professionalId): array
    {
        if ($this->professionals->findById($professionalId) === null) {
            throw new ProfessionalNotFoundException($professionalId);
        }

        return $this->scheduleBlocks->listByProfessional($professionalId);
    }
}
