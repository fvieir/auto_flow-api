<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Application\Whatsapp\DTOs\RequestConversationHandoffDTO;
use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Exceptions\WaConversationNotFoundException;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;

final class RequestConversationHandoffUseCase
{
    public function __construct(private readonly WaConversationRepositoryInterface $conversations)
    {
    }

    public function handle(int $id, RequestConversationHandoffDTO $dto): WaConversation
    {
        if ($this->conversations->findById($id) === null) {
            throw new WaConversationNotFoundException($id);
        }

        return $this->conversations->requestHandoff($id, $dto->subject);
    }
}
