<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Exceptions;

use App\Domain\Shared\Exceptions\BusinessRuleException;

final class TimeSlotUnavailableException extends BusinessRuleException
{
    public function __construct()
    {
        parent::__construct('O horário solicitado não está disponível para este professional.');
    }
}
