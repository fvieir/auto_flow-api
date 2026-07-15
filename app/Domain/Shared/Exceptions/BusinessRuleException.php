<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use RuntimeException;

abstract class BusinessRuleException extends RuntimeException
{
}
