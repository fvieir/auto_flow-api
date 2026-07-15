<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Client;

use App\Domain\Shared\Tenancy\CurrentTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateClientRequest extends FormRequest
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
        $tenantId = app(CurrentTenant::class)->id()->value();

        return [
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'phone')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'document' => ['nullable', 'regex:/^\d{11}$|^\d{14}$/'],
        ];
    }
}
