<?php

declare(strict_types=1);

namespace App\Infrastructure\Integrations\WhatsApp;

use Illuminate\Support\Facades\Http;

/**
 * Client HTTP para a WhatsApp Cloud API (Meta). Payloads seguem a documentação
 * oficial linkada no ROADMAP.md — ver "Documentação oficial WhatsApp".
 */
final class WhatsAppClient
{
    public function __construct(private readonly string $graphApiVersion)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function sendText(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        string $body,
        bool $previewUrl = false,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => $previewUrl,
                'body' => $body,
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function sendImage(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        ?string $mediaId,
        ?string $mediaLink,
        ?string $caption = null,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'image',
            'image' => array_filter([
                'id' => $mediaId,
                'link' => $mediaLink,
                'caption' => $caption,
            ], fn (mixed $value) => $value !== null),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function sendDocument(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        ?string $mediaId,
        ?string $mediaLink,
        ?string $caption = null,
        ?string $filename = null,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'document',
            'document' => array_filter([
                'id' => $mediaId,
                'link' => $mediaLink,
                'caption' => $caption,
                'filename' => $filename,
            ], fn (mixed $value) => $value !== null),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function sendAudio(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        ?string $mediaId,
        ?string $mediaLink,
        bool $voice = false,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'audio',
            'audio' => array_filter([
                'id' => $mediaId,
                'link' => $mediaLink,
                'voice' => $voice,
            ], fn (mixed $value) => $value !== null),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function sendSticker(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        ?string $mediaId,
        ?string $mediaLink,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'sticker',
            'sticker' => array_filter([
                'id' => $mediaId,
                'link' => $mediaLink,
            ], fn (mixed $value) => $value !== null),
        ]);
    }

    /**
     * Envia qualquer subtipo interativo (list, button, cta_url, carousel). O
     * objeto `interactive` é repassado como veio do caller — a validação de
     * schema de cada subtipo é feita no Presentation (Request) e, em último
     * caso, pela própria Meta.
     *
     * @param  array<string, mixed>  $interactive
     * @return array<string, mixed>
     */
    public function sendInteractive(
        string $phoneNumberId,
        string $accessToken,
        string $to,
        array $interactive,
    ): array {
        return $this->send($phoneNumberId, $accessToken, [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => $interactive,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function send(string $phoneNumberId, string $accessToken, array $payload): array
    {
        $response = Http::withToken($accessToken)
            ->baseUrl("https://graph.facebook.com/{$this->graphApiVersion}")
            ->post("/{$phoneNumberId}/messages", $payload);

        $response->throw();

        return $response->json();
    }
}
