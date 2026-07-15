<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Whatsapp;

use App\Domain\Whatsapp\Enums\WaConversationStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateConversationStageRequest extends FormRequest
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
            'stage' => ['required', new Enum(WaConversationStage::class)],
            'appointment_id' => ['nullable', 'integer'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
