<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\DeleteWorkScheduleUseCase;
use Illuminate\Http\Response;

final class DeleteWorkScheduleController
{
    public function __construct(private readonly DeleteWorkScheduleUseCase $useCase)
    {
    }

    public function __invoke(int $professional, int $workSchedule): Response
    {
        $this->useCase->handle($professional, $workSchedule);

        return response()->noContent();
    }
}
