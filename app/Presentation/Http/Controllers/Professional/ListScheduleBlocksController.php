<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\ListScheduleBlocksUseCase;
use App\Presentation\Http\Resources\Professional\ScheduleBlockResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListScheduleBlocksController
{
    public function __construct(private readonly ListScheduleBlocksUseCase $useCase)
    {
    }

    public function __invoke(int $professional): AnonymousResourceCollection
    {
        return ScheduleBlockResource::collection($this->useCase->handle($professional));
    }
}
