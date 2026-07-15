<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Whatsapp;

use App\Domain\Whatsapp\Enums\WaMessageSenderType;
use App\Infrastructure\Persistence\Whatsapp\Models\WaConversationModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class SendWhatsAppMessageRequest extends FormRequest
{
    /**
     * Chamadas com sender_type=agent|system são consideradas caller
     * interno/trusted (job, N8N), sem Sanctum. sender_type=employee exige
     * um usuário Sanctum autenticado e vinculado ao tenant da conversa —
     * evita um atendente de outro tenant enviar mensagem numa conversa que
     * não é dele, e evita spoofar sender_id de outro atendente.
     */
    public function authorize(): bool
    {
        if ($this->input('sender_type') !== WaMessageSenderType::Employee->value) {
            return true;
        }

        $user = $this->user('sanctum');

        if ($user === null) {
            return false;
        }

        $conversation = WaConversationModel::withoutGlobalScopes()->find($this->input('conversation_id'));

        return $conversation !== null && $user->tenants()->where('tenants.id', $conversation->tenant_id)->exists();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'conversation_id' => ['required', 'integer'],
            'type' => ['required', 'in:text,image,document,audio,sticker,interactive'],
            'sender_type' => ['required', new Enum(WaMessageSenderType::class)],
            // sender_id não é lido do body quando sender_type=employee — vem do usuário
            // autenticado (ver SendWhatsAppMessageDTO::fromRequest), então não é obrigatório aqui.
            'sender_id' => ['nullable', 'integer'],
        ];

        return match ($this->input('type')) {
            'text' => $rules + [
                'text' => ['required', 'array'],
                'text.body' => ['required', 'string', 'max:4096'],
                'text.preview_url' => ['sometimes', 'boolean'],
            ],
            'image' => $rules + [
                'image' => ['required', 'array'],
                'image.id' => ['required_without:image.link', 'string'],
                'image.link' => ['required_without:image.id', 'string'],
                'image.caption' => ['nullable', 'string', 'max:1024'],
            ],
            'document' => $rules + [
                'document' => ['required', 'array'],
                'document.id' => ['required_without:document.link', 'string'],
                'document.link' => ['required_without:document.id', 'string'],
                'document.caption' => ['nullable', 'string', 'max:1024'],
                'document.filename' => ['nullable', 'string', 'max:255'],
            ],
            'audio' => $rules + [
                'audio' => ['required', 'array'],
                'audio.id' => ['required_without:audio.link', 'string'],
                'audio.link' => ['required_without:audio.id', 'string'],
                'audio.voice' => ['sometimes', 'boolean'],
            ],
            'sticker' => $rules + [
                'sticker' => ['required', 'array'],
                'sticker.id' => ['required_without:sticker.link', 'string'],
                'sticker.link' => ['required_without:sticker.id', 'string'],
            ],
            'interactive' => $rules + [
                'interactive' => ['required', 'array'],
                'interactive.type' => ['required', 'in:list,button,cta_url,carousel'],
                'interactive.body' => ['required', 'array'],
                'interactive.body.text' => ['required', 'string', 'max:1024'],
                'interactive.action' => ['required', 'array'],
            ],
            default => $rules,
        };
    }
}
