<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Whatsapp;

use App\Infrastructure\Persistence\Whatsapp\Models\WaPhoneNumberModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StartConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'channel_contact_id' => ['nullable', 'integer', 'required_without_all:client_id,phone'],
            'client_id' => ['nullable', 'integer', 'required_without_all:channel_contact_id,phone'],
            'phone' => ['nullable', 'string', 'max:20', 'required_without_all:channel_contact_id,client_id'],
            'name' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'regex:/^\d{11}$|^\d{14}$/'],
            // Só exigido quando o tenant tem mais de 1 (ou nenhum) número WA
            // ativo — com exatamente 1, o UseCase seleciona automaticamente.
            'wa_phone_number_id' => [
                Rule::requiredIf(fn () => WaPhoneNumberModel::where('is_active', true)->count() !== 1),
                'nullable',
                'integer',
            ],
        ];
    }
}
