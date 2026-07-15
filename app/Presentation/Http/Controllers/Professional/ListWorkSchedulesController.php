<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\ListWorkSchedulesUseCase;
use App\Presentation\Http\Resources\Professional\WorkScheduleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListWorkSchedulesController
{
    public function __construct(private readonly ListWorkSchedulesUseCase $useCase)
    {
    }

    public function __invoke(int $professional): AnonymousResourceCollection
    {
        return WorkScheduleResource::collection($this->useCase->handle($professional));
    }
}
