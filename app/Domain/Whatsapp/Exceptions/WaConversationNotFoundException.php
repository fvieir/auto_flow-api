<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class WaConversationNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Conversa {$id} não encontrada.");
    }
}
