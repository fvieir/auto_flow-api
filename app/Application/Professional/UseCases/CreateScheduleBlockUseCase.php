<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\CreateScheduleBlockDTO;
use App\Domain\Professional\Entities\ScheduleBlock;
use App\Domain\Professional\Exceptions\ProfessionalNotFoundException;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;

final class CreateScheduleBlockUseCase
{
    public function __construct(
        private readonly ScheduleBlockRepositoryInterface $scheduleBlocks,
        private readonly ProfessionalRepositoryInterface $professionals,
    ) {
    }

    public function handle(int $professionalId, CreateScheduleBlockDTO $dto): ScheduleBlock
    {
        if ($this->professionals->findById($professionalId) === null) {
            throw new ProfessionalNotFoundException($professionalId);
        }

        $scheduleBlock = ScheduleBlock::createNew($professionalId, $dto->startAt, $dto->endAt, $dto->reason);

        return $this->scheduleBlocks->create($scheduleBlock);
    }
}
