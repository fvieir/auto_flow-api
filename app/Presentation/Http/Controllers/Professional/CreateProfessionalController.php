<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\CreateProfessionalDTO;
use App\Application\Professional\UseCases\CreateProfessionalUseCase;
use App\Presentation\Http\Requests\Professional\CreateProfessionalRequest;
use App\Presentation\Http\Resources\Professional\ProfessionalResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateProfessionalController
{
    public function __construct(private readonly CreateProfessionalUseCase $useCase)
    {
    }

    public function __invoke(CreateProfessionalRequest $request): JsonResponse
    {
        $professional = $this->useCase->handle(CreateProfessionalDTO::fromRequest($request));

        return ProfessionalResource::make($professional)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
