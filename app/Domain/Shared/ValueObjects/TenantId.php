<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;

final class TenantId
{
    public function __construct(public readonly int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('TenantId deve ser um inteiro positivo.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
