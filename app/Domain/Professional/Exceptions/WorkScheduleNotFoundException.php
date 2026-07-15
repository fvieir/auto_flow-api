<?php

declare(strict_types=1);

namespace App\Domain\Professional\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class WorkScheduleNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Horário de trabalho {$id} não encontrado.");
    }
}
