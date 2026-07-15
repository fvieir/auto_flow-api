<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class WaPhoneNumberNotFoundException extends EntityNotFoundException
{
    public function __construct(string $phoneNumberId)
    {
        parent::__construct("Número WhatsApp {$phoneNumberId} não encontrado.");
    }
}
