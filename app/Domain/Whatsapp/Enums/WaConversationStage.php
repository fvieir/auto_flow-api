<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Enums;

enum WaConversationStage: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case QuoteSent = 'quote_sent';
    case AwaitingDate = 'awaiting_date';
    case Converted = 'converted';
    case Lost = 'lost';
}
