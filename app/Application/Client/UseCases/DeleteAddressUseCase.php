<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Client\Exceptions\ClientAddressNotFoundException;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;

final class DeleteAddressUseCase
{
    public function __construct(private readonly ClientAddressRepositoryInterface $addresses)
    {
    }

    public function handle(int $clientId, int $id): void
    {
        $address = $this->addresses->findById($id);

        if ($address === null || $address->clientId() !== $clientId) {
            throw new ClientAddressNotFoundException($id);
        }

        $this->addresses->delete($id);
    }
}
