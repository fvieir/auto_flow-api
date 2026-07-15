<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\DTOs\UpdateProfessionalDTO;
use App\Application\Professional\UseCases\UpdateProfessionalUseCase;
use App\Presentation\Http\Requests\Professional\UpdateProfessionalRequest;
use App\Presentation\Http\Resources\Professional\ProfessionalResource;
use Illuminate\Http\JsonResponse;

final class UpdateProfessionalController
{
    public function __construct(private readonly UpdateProfessionalUseCase $useCase)
    {
    }

    public function __invoke(int $professional, UpdateProfessionalRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($professional, UpdateProfessionalDTO::fromRequest($request));

        return ProfessionalResource::make($updated)->response();
    }
}
