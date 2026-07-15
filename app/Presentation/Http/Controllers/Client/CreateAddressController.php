<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\DTOs\CreateAddressDTO;
use App\Application\Client\UseCases\CreateAddressUseCase;
use App\Presentation\Http\Requests\Client\CreateAddressRequest;
use App\Presentation\Http\Resources\Client\ClientAddressResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateAddressController
{
    public function __construct(private readonly CreateAddressUseCase $useCase)
    {
    }

    public function __invoke(int $client, CreateAddressRequest $request): JsonResponse
    {
        $address = $this->useCase->handle($client, CreateAddressDTO::fromRequest($request));

        return ClientAddressResource::make($address)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
