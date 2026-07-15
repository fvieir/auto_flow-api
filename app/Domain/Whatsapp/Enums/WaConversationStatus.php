<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Enums;

enum WaConversationStatus: string
{
    case Open = 'open';
    case Resolved = 'resolved';
}
