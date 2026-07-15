<?php

declare(strict_types=1);

namespace App\Domain\Service\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class ServiceNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Serviço {$id} não encontrado.");
    }
}
