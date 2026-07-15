<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Service;

use App\Application\Service\DTOs\CreateServiceDTO;
use App\Application\Service\UseCases\CreateServiceUseCase;
use App\Presentation\Http\Requests\Service\CreateServiceRequest;
use App\Presentation\Http\Resources\Service\ServiceResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateServiceController
{
    public function __construct(private readonly CreateServiceUseCase $useCase)
    {
    }

    public function __invoke(CreateServiceRequest $request): JsonResponse
    {
        $service = $this->useCase->handle(CreateServiceDTO::fromRequest($request));

        return ServiceResource::make($service)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
