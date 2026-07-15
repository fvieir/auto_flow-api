<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WebhookEvent;

interface WebhookEventRepositoryInterface
{
    public function create(WebhookEvent $event): WebhookEvent;

    public function findById(int $id): ?WebhookEvent;

    public function update(WebhookEvent $event): WebhookEvent;
}
