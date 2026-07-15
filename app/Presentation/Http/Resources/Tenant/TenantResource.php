<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Tenant;

use App\Domain\Tenant\Entities\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Tenant $resource
 */
final class TenantResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tenant = $this->resource;

        return [
            'id' => $tenant->id()?->value(),
            'name' => $tenant->name(),
            'slug' => $tenant->slug()->value(),
            'status' => $tenant->status()->value,
            'timezone' => $tenant->timezone(),
        ];
    }
}
