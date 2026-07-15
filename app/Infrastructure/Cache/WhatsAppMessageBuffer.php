<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Illuminate\Support\Facades\Redis;

final class WhatsAppMessageBuffer
{
    private const TTL_SECONDS = 60;

    /**
     * Empurra uma mensagem na lista da conversa e retorna o tamanho da lista
     * após o push (1 = era a primeira mensagem do lote atual).
     *
     * @param  array<string, mixed>  $entry
     */
    public function push(int $conversationId, array $entry): int
    {
        $messagesKey = $this->messagesKey($conversationId);
        $metaKey = $this->metaKey($conversationId);

        $length = (int) Redis::rpush($messagesKey, json_encode($entry));
        Redis::expire($messagesKey, self::TTL_SECONDS);

        if ($length === 1) {
            Redis::hsetnx($metaKey, 'first_message_at', microtime(true));
        }

        Redis::expire($metaKey, self::TTL_SECONDS);

        return $length;
    }

    public function incrementVersion(int $conversationId): int
    {
        $metaKey = $this->metaKey($conversationId);
        $version = (int) Redis::hincrby($metaKey, 'version', 1);
        Redis::expire($metaKey, self::TTL_SECONDS);

        return $version;
    }

    public function currentVersion(int $conversationId): int
    {
        return (int) (Redis::hget($this->metaKey($conversationId), 'version') ?? 0);
    }

    public function firstMessageAt(int $conversationId): ?float
    {
        $value = Redis::hget($this->metaKey($conversationId), 'first_message_at');

        return $value !== null ? (float) $value : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function drain(int $conversationId): array
    {
        $messagesKey = $this->messagesKey($conversationId);
        $metaKey = $this->metaKey($conversationId);

        $raw = Redis::lrange($messagesKey, 0, -1);

        Redis::del($messagesKey, $metaKey);

        return array_map(fn (string $item) => json_decode($item, true), $raw);
    }

    private function messagesKey(int $conversationId): string
    {
        return "wa:buffer:{$conversationId}:messages";
    }

    private function metaKey(int $conversationId): string
    {
        return "wa:buffer:{$conversationId}:meta";
    }
}
