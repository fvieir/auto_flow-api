<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class AppointmentNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Appointment {$id} não encontrado.");
    }
}
