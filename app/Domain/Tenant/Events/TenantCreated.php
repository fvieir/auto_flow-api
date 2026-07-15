<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Events;

use App\Domain\Shared\ValueObjects\TenantId;

final class TenantCreated
{
    public function __construct(
        public readonly TenantId $tenantId,
        public readonly int $adminUserId,
    ) {
    }
}
