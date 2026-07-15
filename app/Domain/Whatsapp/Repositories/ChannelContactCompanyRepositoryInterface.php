<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\ChannelContactCompany;

interface ChannelContactCompanyRepositoryInterface
{
    public function findByContactAndTenant(int $channelContactId, int $tenantId): ?ChannelContactCompany;

    public function create(ChannelContactCompany $company): ChannelContactCompany;
}
