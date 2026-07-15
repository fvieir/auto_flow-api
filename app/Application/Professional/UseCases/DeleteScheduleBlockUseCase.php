<?php

declare(strict_types=1);

namespace App\Application\Professional\UseCases;

use App\Domain\Professional\Exceptions\ScheduleBlockNotFoundException;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;

final class DeleteScheduleBlockUseCase
{
    public function __construct(private readonly ScheduleBlockRepositoryInterface $scheduleBlocks)
    {
    }

    public function handle(int $professionalId, int $id): void
    {
        $scheduleBlock = $this->scheduleBlocks->findById($id);

        if ($scheduleBlock === null || $scheduleBlock->professionalId() !== $professionalId) {
            throw new ScheduleBlockNotFoundException($id);
        }

        $this->scheduleBlocks->delete($id);
    }
}
