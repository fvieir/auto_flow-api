<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaMessage;
use App\Domain\Whatsapp\Enums\WaMessageStatus;
use DateTimeImmutable;

interface WaMessageRepositoryInterface
{
    public function create(WaMessage $message): WaMessage;

    public function findById(int $id): ?WaMessage;

    public function findByWamid(string $wamid): ?WaMessage;

    public function updateStatus(int $id, WaMessageStatus $status, DateTimeImmutable $updatedAt, ?string $error): WaMessage;
}
