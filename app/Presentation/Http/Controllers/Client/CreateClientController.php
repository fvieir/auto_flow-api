<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\DTOs\CreateClientDTO;
use App\Application\Client\UseCases\CreateClientUseCase;
use App\Presentation\Http\Requests\Client\CreateClientRequest;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateClientController
{
    public function __construct(private readonly CreateClientUseCase $useCase)
    {
    }

    public function __invoke(CreateClientRequest $request): JsonResponse
    {
        $client = $this->useCase->handle(CreateClientDTO::fromRequest($request));

        return ClientResource::make($client)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
