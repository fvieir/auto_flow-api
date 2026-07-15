<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Exceptions;

use App\Domain\Shared\Exceptions\EntityNotFoundException;

final class ChannelContactNotFoundException extends EntityNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct("Contato {$id} não encontrado.");
    }
}
