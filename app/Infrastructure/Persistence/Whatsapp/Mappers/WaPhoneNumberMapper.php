<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Mappers;

use App\Domain\Whatsapp\Entities\WaPhoneNumber;
use App\Infrastructure\Persistence\Whatsapp\Models\WaPhoneNumberModel;

final class WaPhoneNumberMapper
{
    public function toDomain(WaPhoneNumberModel $model): WaPhoneNumber
    {
        return WaPhoneNumber::fromPersistence(
            id: (int) $model->id,
            tenantId: (int) $model->tenant_id,
            phoneNumberId: $model->phone_number_id,
            displayPhoneNumber: $model->display_phone_number,
            verifiedName: $model->verified_name,
            qualityRating: $model->quality_rating,
            accessToken: $model->access_token,
            isActive: (bool) $model->is_active,
        );
    }
}
