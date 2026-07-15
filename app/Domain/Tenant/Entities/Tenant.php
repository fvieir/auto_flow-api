<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Entities;

use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Enums\TenantStatus;
use App\Domain\Tenant\ValueObjects\TenantSlug;

/**
 * Entidade de domínio do Tenant: regra e comportamento (status, slug, fuso).
 * A persistência mora em Infrastructure\Persistence\Tenant\Models\TenantModel;
 * o mapeamento entre as duas é do TenantMapper. Ver docs/multitenancy.md.
 */
final class Tenant
{
    private function __construct(
        private ?TenantId $id,
        private string $name,
        private TenantSlug $slug,
        private TenantStatus $status,
        private string $timezone,
    ) {
    }

    public static function createNew(string $name, TenantSlug $slug, string $timezone): self
    {
        return new self(
            id: null,
            name: $name,
            slug: $slug,
            status: TenantStatus::Trial,
            timezone: $timezone,
        );
    }

    public static function fromPersistence(
        TenantId $id,
        string $name,
        TenantSlug $slug,
        TenantStatus $status,
        string $timezone,
    ): self {
        return new self($id, $name, $slug, $status, $timezone);
    }

    public function id(): ?TenantId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): TenantSlug
    {
        return $this->slug;
    }

    public function status(): TenantStatus
    {
        return $this->status;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }
}
