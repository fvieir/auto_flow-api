<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

final class SearchClientsRequest extends FormRequest
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
            'q' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
