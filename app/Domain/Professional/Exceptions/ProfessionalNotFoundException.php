<?php

declare(strict_types=1);

namespace App\Domain\Professional\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class ProfessionalNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Professional {$id} não encontrado.");
    }
}
