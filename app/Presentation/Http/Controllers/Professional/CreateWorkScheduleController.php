<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\CreateWorkScheduleDTO;
use App\Application\Professional\UseCases\CreateWorkScheduleUseCase;
use App\Presentation\Http\Requests\Professional\CreateWorkScheduleRequest;
use App\Presentation\Http\Resources\Professional\WorkScheduleResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateWorkScheduleController
{
    public function __construct(private readonly CreateWorkScheduleUseCase $useCase)
    {
    }

    public function __invoke(int $professional, CreateWorkScheduleRequest $request): JsonResponse
    {
        $workSchedule = $this->useCase->handle($professional, CreateWorkScheduleDTO::fromRequest($request));

        return WorkScheduleResource::make($workSchedule)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
