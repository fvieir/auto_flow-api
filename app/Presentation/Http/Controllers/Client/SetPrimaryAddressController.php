<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\SetPrimaryAddressUseCase;
use App\Presentation\Http\Resources\Client\ClientAddressResource;
use Illuminate\Http\JsonResponse;

final class SetPrimaryAddressController
{
    public function __construct(private readonly SetPrimaryAddressUseCase $useCase)
    {
    }

    public function __invoke(int $client, int $address): JsonResponse
    {
        $updated = $this->useCase->handle($client, $address);

        return ClientAddressResource::make($updated)->response();
    }
}
