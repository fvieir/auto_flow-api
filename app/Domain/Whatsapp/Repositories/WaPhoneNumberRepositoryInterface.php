<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Repositories;

use App\Domain\Whatsapp\Entities\WaPhoneNumber;

interface WaPhoneNumberRepositoryInterface
{
    public function findByPhoneNumberId(string $phoneNumberId): ?WaPhoneNumber;

    public function findById(int $id): ?WaPhoneNumber;

    /**
     * @return list<WaPhoneNumber>
     */
    public function listActive(): array;
}
