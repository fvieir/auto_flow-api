<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\DeleteClientUseCase;
use Illuminate\Http\Response;

final class DeleteClientController
{
    public function __construct(private readonly DeleteClientUseCase $useCase)
    {
    }

    public function __invoke(int $client): Response
    {
        $this->useCase->handle($client);

        return response()->noContent();
    }
}
