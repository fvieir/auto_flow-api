<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Service;

use App\Application\Service\UseCases\DeleteServiceUseCase;
use Illuminate\Http\Response;

final class DeleteServiceController
{
    public function __construct(private readonly DeleteServiceUseCase $useCase)
    {
    }

    public function __invoke(int $service): Response
    {
        $this->useCase->handle($service);

        return response()->noContent();
    }
}
