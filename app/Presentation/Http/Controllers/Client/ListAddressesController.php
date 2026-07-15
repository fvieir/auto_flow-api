<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\ListAddressesUseCase;
use App\Presentation\Http\Resources\Client\ClientAddressResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListAddressesController
{
    public function __construct(private readonly ListAddressesUseCase $useCase)
    {
    }

    public function __invoke(int $client): AnonymousResourceCollection
    {
        return ClientAddressResource::collection($this->useCase->handle($client));
    }
}
