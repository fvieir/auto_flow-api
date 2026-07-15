<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\UpdateScheduleBlockDTO;
use App\Application\Professional\UseCases\UpdateScheduleBlockUseCase;
use App\Presentation\Http\Requests\Professional\UpdateScheduleBlockRequest;
use App\Presentation\Http\Resources\Professional\ScheduleBlockResource;
use Illuminate\Http\JsonResponse;

final class UpdateScheduleBlockController
{
    public function __construct(private readonly UpdateScheduleBlockUseCase $useCase)
    {
    }

    public function __invoke(int $professional, int $scheduleBlock, UpdateScheduleBlockRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($professional, $scheduleBlock, UpdateScheduleBlockDTO::fromRequest($request));

        return ScheduleBlockResource::make($updated)->response();
    }
}
