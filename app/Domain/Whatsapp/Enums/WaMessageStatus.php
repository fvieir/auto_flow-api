<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Enums;

enum WaMessageStatus: string
{
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Read = 'read';
    case Failed = 'failed';
}
