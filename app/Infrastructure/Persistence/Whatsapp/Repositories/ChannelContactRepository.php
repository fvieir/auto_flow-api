<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\ChannelContact;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Infrastructure\Persistence\Whatsapp\Mappers\ChannelContactMapper;
use App\Infrastructure\Persistence\Whatsapp\Models\ChannelContactModel;

final class ChannelContactRepository implements ChannelContactRepositoryInterface
{
    public function __construct(private readonly ChannelContactMapper $mapper)
    {
    }

    public function findActiveByChannelAndPhone(string $channel, string $phone): ?ChannelContact
    {
        $model = ChannelContactModel::where('channel', $channel)
            ->where('phone', $phone)
            ->whereNull('unlinked_at')
            ->first();

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function findById(int $id): ?ChannelContact
    {
        $model = ChannelContactModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function create(ChannelContact $contact): ChannelContact
    {
        $model = ChannelContactModel::create([
            'channel' => $contact->channel(),
            'phone' => $contact->phone(),
            'external_id' => $contact->externalId(),
            'last_interaction_at' => now(),
        ]);

        return $this->mapper->toDomain($model);
    }
}
