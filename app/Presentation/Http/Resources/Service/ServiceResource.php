<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Service;

use App\Domain\Service\Entities\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Service $resource
 */
final class ServiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $service = $this->resource;

        return [
            'id' => $service->id(),
            'name' => $service->name(),
            'duration_minutes' => $service->durationMinutes(),
            'price' => $service->price(),
        ];
    }
}
