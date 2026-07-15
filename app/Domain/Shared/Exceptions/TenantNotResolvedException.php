<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use RuntimeException;

final class TenantNotResolvedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Nenhum tenant resolvido para o context atual.');
    }
}
