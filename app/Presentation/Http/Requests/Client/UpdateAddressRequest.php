<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAddressRequest extends FormRequest
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
            'postal_code' => ['required', 'string', 'max:10'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
