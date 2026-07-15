<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Exceptions;

use App\Domain\Shared\Exceptions\BusinessRuleException;

final class InvalidStatusTransitionException extends BusinessRuleException
{
    public function __construct(string $reason)
    {
        parent::__construct($reason);
    }
}
