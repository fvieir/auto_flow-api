<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\CreateScheduleBlockDTO;
use App\Application\Professional\UseCases\CreateScheduleBlockUseCase;
use App\Presentation\Http\Requests\Professional\CreateScheduleBlockRequest;
use App\Presentation\Http\Resources\Professional\ScheduleBlockResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateScheduleBlockController
{
    public function __construct(private readonly CreateScheduleBlockUseCase $useCase)
    {
    }

    public function __invoke(int $professional, CreateScheduleBlockRequest $request): JsonResponse
    {
        $scheduleBlock = $this->useCase->handle($professional, CreateScheduleBlockDTO::fromRequest($request));

        return ScheduleBlockResource::make($scheduleBlock)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
