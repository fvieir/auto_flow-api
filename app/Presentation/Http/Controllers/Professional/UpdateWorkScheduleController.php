<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\UpdateWorkScheduleDTO;
use App\Application\Professional\UseCases\UpdateWorkScheduleUseCase;
use App\Presentation\Http\Requests\Professional\UpdateWorkScheduleRequest;
use App\Presentation\Http\Resources\Professional\WorkScheduleResource;
use Illuminate\Http\JsonResponse;

final class UpdateWorkScheduleController
{
    public function __construct(private readonly UpdateWorkScheduleUseCase $useCase)
    {
    }

    public function __invoke(int $professional, int $workSchedule, UpdateWorkScheduleRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($professional, $workSchedule, UpdateWorkScheduleDTO::fromRequest($request));

        return WorkScheduleResource::make($updated)->response();
    }
}
