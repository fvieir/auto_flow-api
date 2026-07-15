<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\ListClientsUseCase;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListClientsController
{
    public function __construct(private readonly ListClientsUseCase $useCase)
    {
    }

    public function __invoke(): AnonymousResourceCollection
    {
        return ClientResource::collection($this->useCase->handle());
    }
}
