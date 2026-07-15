<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Client;

use App\Domain\Client\Entities\ClientAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read ClientAddress $resource
 */
final class ClientAddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = $this->resource;

        return [
            'id' => $address->id(),
            'client_id' => $address->clientId(),
            'postal_code' => $address->postalCode(),
            'street' => $address->street(),
            'number' => $address->number(),
            'complement' => $address->complement(),
            'neighborhood' => $address->neighborhood(),
            'city' => $address->city(),
            'state' => $address->state(),
            'is_primary' => $address->isPrimary(),
        ];
    }
}
