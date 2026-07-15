<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Professional;

use App\Domain\Professional\Entities\Professional;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Professional $resource
 */
final class ProfessionalResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $professional = $this->resource;

        return [
            'id' => $professional->id(),
            'name' => $professional->name(),
            'phone' => $professional->phone(),
            'email' => $professional->email(),
        ];
    }
}
