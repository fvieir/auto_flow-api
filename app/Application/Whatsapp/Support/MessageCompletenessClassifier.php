<?php

declare(strict_types=1);

namespace App\Application\Whatsapp\Support;

final class MessageCompletenessClassifier
{
    private const TERMINAL_PUNCTUATION = ['.', '!', '?'];

    private const CLOSING_EMOJIS = ['👍', '🙏', '✅', '😊'];

    private const COMPLETE_PHRASES = [
        'oi', 'ola', 'ola!', 'bom dia', 'boa tarde', 'boa noite',
        'obrigado', 'obrigada', 'valeu',
    ];

    private const TRAILING_CONJUNCTIONS = [
        'e', 'mas', 'entao', 'so que', 'pra', 'porque', 'que', 'de', 'da', 'no', 'na',
    ];

    public function classify(string $text, bool $hasPriorBufferedMessage): string
    {
        $trimmed = trim($text);
        $normalized = $this->normalize($trimmed);

        if (in_array($normalized, self::COMPLETE_PHRASES, true)) {
            return 'complete';
        }

        $lastChar = mb_substr($trimmed, -1);

        if (in_array($lastChar, self::TERMINAL_PUNCTUATION, true)) {
            return 'complete';
        }

        foreach (self::CLOSING_EMOJIS as $emoji) {
            if (str_ends_with($trimmed, $emoji)) {
                return 'complete';
            }
        }

        if ($lastChar === ',' || str_ends_with($trimmed, '...')) {
            return 'fragmented';
        }

        foreach (self::TRAILING_CONJUNCTIONS as $conjunction) {
            if (str_ends_with($normalized, ' ' . $conjunction) || $normalized === $conjunction) {
                return 'fragmented';
            }
        }

        $wordCount = count(array_filter(explode(' ', $normalized)));

        if ($wordCount <= 2) {
            return 'fragmented';
        }

        if ($hasPriorBufferedMessage) {
            return 'fragmented';
        }

        return 'complete';
    }

    private function normalize(string $text): string
    {
        $lower = mb_strtolower($text);
        $withoutAccents = strtr($lower, [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
            'é' => 'e', 'ê' => 'e',
            'í' => 'i',
            'ó' => 'o', 'õ' => 'o', 'ô' => 'o',
            'ú' => 'u',
            'ç' => 'c',
        ]);

        return trim(preg_replace('/[.!?,]+$/u', '', $withoutAccents) ?? $withoutAccents);
    }
}
