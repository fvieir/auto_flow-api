<?php

declare(strict_types=1);

namespace App\Presentation\Queue\Jobs;

use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Infrastructure\Cache\WhatsAppMessageBuffer;
use App\Infrastructure\Integrations\Agent\N8nAgentNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class FlushMessageBufferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public readonly int $tenantId,
        public readonly int $conversationId,
        public readonly int $expectedVersion,
    ) {
    }

    public function handle(
        WhatsAppMessageBuffer $buffer,
        WaConversationRepositoryInterface $conversations,
        ChannelContactRepositoryInterface $channelContacts,
        ChannelContactCompanyRepositoryInterface $channelContactCompanies,
        N8nAgentNotifier $notifier,
        CurrentTenant $currentTenant,
    ): void {
        if ($buffer->currentVersion($this->conversationId) !== $this->expectedVersion) {
            return;
        }

        $messages = $buffer->drain($this->conversationId);

        if ($messages === []) {
            return;
        }

        $currentTenant->set(new TenantId($this->tenantId));

        $conversation = $conversations->findById($this->conversationId);

        if ($conversation === null) {
            return;
        }

        $contact = $channelContacts->findById($conversation->channelContactId());
        $company = $contact !== null
            ? $channelContactCompanies->findByContactAndTenant($contact->id(), $this->tenantId)
            : null;

        $text = implode("\n", array_map(fn (array $entry) => (string) ($entry['body'] ?? ''), $messages));

        $notifier->notify([
            'tenant_id' => $this->tenantId,
            'conversation_id' => $this->conversationId,
            'client_id' => $company?->clientId(),
            'wa_phone_number_id' => $conversation->waPhoneNumberId(),
            'phone' => $contact?->phone(),
            'message' => $text,
            'buffered_message_ids' => array_map(fn (array $entry) => $entry['wa_message_id'], $messages),
        ]);
    }
}
