<?php

declare(strict_types=1);

namespace App\Domain\Client\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class ClientNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Client {$id} não encontrado.");
    }
}
