<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Enums;

enum WaMessageSenderType: string
{
    case Contact = 'contact';
    case Agent = 'agent';
    case Employee = 'employee';
    case System = 'system';
}
