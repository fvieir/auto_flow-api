<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Service;

use App\Application\Service\DTOs\UpdateServiceDTO;
use App\Application\Service\UseCases\UpdateServiceUseCase;
use App\Presentation\Http\Requests\Service\UpdateServiceRequest;
use App\Presentation\Http\Resources\Service\ServiceResource;
use Illuminate\Http\JsonResponse;

final class UpdateServiceController
{
    public function __construct(private readonly UpdateServiceUseCase $useCase)
    {
    }

    public function __invoke(int $service, UpdateServiceRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($service, UpdateServiceDTO::fromRequest($request));

        return ServiceResource::make($updated)->response();
    }
}
