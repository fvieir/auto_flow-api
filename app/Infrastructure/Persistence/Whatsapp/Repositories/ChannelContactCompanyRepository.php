<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\ChannelContactCompany;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\ChannelContactCompanyMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\ChannelContactCompanyModel;

final class ChannelContactCompanyRepository implements ChannelContactCompanyRepositoryInterface
{
    public function __construct(private readonly ChannelContactCompanyMapper $mapper)
    {
    }

    public function findByContactAndTenant(int $channelContactId, int $tenantId): ?ChannelContactCompany
    {
        $model = ChannelContactCompanyModel::withoutGlobalScopes()
            ->where('channel_contact_id', $channelContactId)
            ->where('tenant_id', $tenantId)
            ->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function create(ChannelContactCompany $company): ChannelContactCompany
    {
        $model = ChannelContactCompanyModel::create([
            'channel_contact_id' => $company->channelContactId(),
            'tenant_id' => $company->tenantId(),
            'client_id' => $company->clientId(),
            'last_interaction_at' => now(),
        ]);

        return $this->mapper->toDomain($model);
    }
}
