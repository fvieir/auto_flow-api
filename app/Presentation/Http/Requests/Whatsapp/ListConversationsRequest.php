<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Whatsapp;

use App\Domain\Whatsapp\Enums\WaConversationStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class ListConversationsRequest extends FormRequest
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
            'stage' => ['sometimes', new Enum(WaConversationStage::class)],
        ];
    }
}
