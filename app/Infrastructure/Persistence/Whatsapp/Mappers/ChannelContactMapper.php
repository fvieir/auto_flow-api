<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\ChannelContact;
use App\Infrastructure\Persistence\Whatsapp\Models\ChannelContactModel;

final class ChannelContactMapper
{
    public function toDomain(ChannelContactModel $model): ChannelContact
    {
        return ChannelContact::fromPersistence(
            id: (int) $model->id,
            channel: $model->channel,
            phone: $model->phone,
            externalId: $model->external_id,
        );
    }
}
