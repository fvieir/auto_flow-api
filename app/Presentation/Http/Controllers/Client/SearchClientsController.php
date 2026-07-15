<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\SearchClientsUseCase;
use App\Presentation\Http\Requests\Client\SearchClientsRequest;
use App\Presentation\Http\Resources\Client\ClientResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SearchClientsController
{
    public function __construct(private readonly SearchClientsUseCase $useCase)
    {
    }

    public function __invoke(SearchClientsRequest $request): AnonymousResourceCollection
    {
        /** @var string $query */
        $query = $request->validated('q');

        return ClientResource::collection($this->useCase->handle($query));
    }
}
