<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Whatsapp;

use Illuminate\Foundation\Http\FormRequest;

final class ResolveWhatsAppContextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
            'phone_number_id' => ['required', 'string'],
        ];
    }
}
