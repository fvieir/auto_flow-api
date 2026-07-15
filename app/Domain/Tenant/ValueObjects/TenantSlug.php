<?php

declare(strict_types=1);

namespace App\Domain\Tenant\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

final class TenantSlug
{
    private const PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    public function __construct(public readonly string $value)
    {
        if (! preg_match(self::PATTERN, $value)) {
            throw new InvalidArgumentException("Slug de tenant inválido: {$value}");
        }
    }

    public static function fromName(string $name): self
    {
        return new self(Str::slug($name));
    }

    public function value(): string
    {
        return $this->value;
    }
}
