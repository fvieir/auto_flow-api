<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\ChannelContact;

interface ChannelContactRepositoryInterface
{
    public function findActiveByChannelAndPhone(string $channel, string $phone): ?ChannelContact;

    public function findById(int $id): ?ChannelContact;

    public function create(ChannelContact $contact): ChannelContact;
}
