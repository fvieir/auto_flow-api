<?php

declare(strict_types=1);

namespace App\Domain\Shared\Tenancy;

use App\Domain\Shared\Exceptions\TenantNotResolvedException;
use App\Domain\Shared\ValueObjects\TenantId;

/**
 * Singleton de context (bind no ServiceProvider) que guarda o tenant atual
 * pela duração do request/job. Ver docs/multitenancy.md.
 */
final class CurrentTenant
{
    private ?TenantId $tenantId = null;

    public function set(TenantId $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function id(): TenantId
    {
        return $this->tenantId ?? throw new TenantNotResolvedException();
    }

    public function isResolved(): bool
    {
        return $this->tenantId !== null;
    }

    public function clear(): void
    {
        $this->tenantId = null;
    }
}
