<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;

final class DeleteClientUseCase
{
    public function __construct(private readonly ClientRepositoryInterface $clients)
    {
    }

    public function handle(int $id): void
    {
        if ($this->clients->findById($id) === null) {
            throw new ClientNotFoundException($id);
        }

        $this->clients->delete($id);
    }
}
