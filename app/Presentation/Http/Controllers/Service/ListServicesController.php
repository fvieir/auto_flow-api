<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Service;

use App\Application\Service\UseCases\ListServicesUseCase;
use App\Presentation\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListServicesController
{
    public function __construct(private readonly ListServicesUseCase $useCase)
    {
    }

    public function __invoke(): AnonymousResourceCollection
    {
        return ServiceResource::collection($this->useCase->handle());
    }
}
