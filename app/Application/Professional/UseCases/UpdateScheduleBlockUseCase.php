<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Application\Professional\DTOs\UpdateScheduleBlockDTO;
use App\Domain\Professional\Entities\ScheduleBlock;
use App\Domain\Professional\Exceptions\ScheduleBlockNotFoundException;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;

final class UpdateScheduleBlockUseCase
{
    public function __construct(private readonly ScheduleBlockRepositoryInterface $scheduleBlocks)
    {
    }

    public function handle(int $professionalId, int $id, UpdateScheduleBlockDTO $dto): ScheduleBlock
    {
        $scheduleBlock = $this->scheduleBlocks->findById($id);

        if ($scheduleBlock === null || $scheduleBlock->professionalId() !== $professionalId) {
            throw new ScheduleBlockNotFoundException($id);
        }

        $updated = $scheduleBlock->withDetails($dto->startAt, $dto->endAt, $dto->reason);

        return $this->scheduleBlocks->update($updated);
    }
}
