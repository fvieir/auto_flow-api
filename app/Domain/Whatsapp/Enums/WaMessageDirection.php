<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Enums;

enum WaMessageDirection: string
{
    case Inbound = 'inbound';
    case Outbound = 'outbound';
}
