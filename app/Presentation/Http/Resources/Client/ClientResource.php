<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Client;

use App\Domain\Client\Entities\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Client $resource
 */
final class ClientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $client = $this->resource;

        return [
            'id' => $client->id(),
            'phone' => $client->phone(),
            'name' => $client->name(),
            'email' => $client->email(),
            'document' => $client->document(),
        ];
    }
}
