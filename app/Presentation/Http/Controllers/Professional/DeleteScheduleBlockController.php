<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\DeleteScheduleBlockUseCase;
use Illuminate\Http\Response;

final class DeleteScheduleBlockController
{
    public function __construct(private readonly DeleteScheduleBlockUseCase $useCase)
    {
    }

    public function __invoke(int $professional, int $scheduleBlock): Response
    {
        $this->useCase->handle($professional, $scheduleBlock);

        return response()->noContent();
    }
}
