<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Client;

use App\Application\Client\UseCases\DeleteAddressUseCase;
use Illuminate\Http\Response;

final class DeleteAddressController
{
    public function __construct(private readonly DeleteAddressUseCase $useCase)
    {
    }

    public function __invoke(int $client, int $address): Response
    {
        $this->useCase->handle($client, $address);

        return response()->noContent();
    }
}
