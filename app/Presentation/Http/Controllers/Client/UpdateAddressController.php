<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\DTOs\UpdateAddressDTO;
use App\Application\Client\UseCases\UpdateAddressUseCase;
use App\Presentation\Http\Requests\Client\UpdateAddressRequest;
use App\Presentation\Http\Resources\Client\ClientAddressResource;
use Illuminate\Http\JsonResponse;

final class UpdateAddressController
{
    public function __construct(private readonly UpdateAddressUseCase $useCase)
    {
    }

    public function __invoke(int $client, int $address, UpdateAddressRequest $request): JsonResponse
    {
        $updated = $this->useCase->handle($client, $address, UpdateAddressDTO::fromRequest($request));

        return ClientAddressResource::make($updated)->response();
    }
}
