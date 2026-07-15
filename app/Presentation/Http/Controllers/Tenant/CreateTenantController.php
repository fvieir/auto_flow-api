<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Tenant;

use App\Application\Tenant\DTOs\CreateTenantDTO;
use App\Application\Tenant\UseCases\CreateTenantUseCase;
use App\Presentation\Http\Requests\Tenant\CreateTenantRequest;
use App\Presentation\Http\Resources\Tenant\TenantResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateTenantController
{
    public function __construct(private readonly CreateTenantUseCase $useCase)
    {
    }

    public function __invoke(CreateTenantRequest $request): JsonResponse
    {
        $tenant = $this->useCase->handle(CreateTenantDTO::fromRequest($request));

        return TenantResource::make($tenant)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
