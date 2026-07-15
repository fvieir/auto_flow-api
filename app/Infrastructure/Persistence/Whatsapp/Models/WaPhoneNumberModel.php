<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Models;

use App\Infrastructure\Persistence\Shared\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WaPhoneNumberModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'wa_phone_numbers';

    protected $fillable = [
        'tenant_id',
        'phone_number_id',
        'display_phone_number',
        'verified_name',
        'quality_rating',
        'access_token',
        'is_active',
    ];

    protected $casts = [
        'access_token' => 'encrypted',
        'is_active' => 'boolean',
    ];
}
