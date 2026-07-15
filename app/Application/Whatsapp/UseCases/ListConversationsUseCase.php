<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\UseCases;

use App\Domain\Whatsapp\Entities\WaConversation;
use App\Domain\Whatsapp\Enums\WaConversationStage;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;

final class ListConversationsUseCase
{
    public function __construct(private readonly WaConversationRepositoryInterface $conversations)
    {
    }

    /**
     * @return list<WaConversation>
     */
    public function handle(?string $stage = null): array
    {
        return $this->conversations->list($stage !== null ? WaConversationStage::from($stage) : null);
    }
}
