<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\ChannelContactCompany;
use App\Infrastructure\Persistence\Whatsapp\Models\ChannelContactCompanyModel;

final class ChannelContactCompanyMapper
{
    public function toDomain(ChannelContactCompanyModel $model): ChannelContactCompany
    {
        return ChannelContactCompany::fromPersistence(
            id: (int) $model->id,
            channelContactId: (int) $model->channel_contact_id,
            tenantId: (int) $model->tenant_id,
            clientId: $model->client_id !== null ? (int) $model->client_id : null,
        );
    }
}
