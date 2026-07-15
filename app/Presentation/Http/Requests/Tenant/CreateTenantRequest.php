<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Onboarding interno do SaaS — sem tenant/usuário no context ainda.
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:tenants,slug'],
            'timezone' => ['sometimes', 'string', 'timezone'],
            'admin' => ['required', 'array'],
            'admin.name' => ['required', 'string', 'max:255'],
            'admin.email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin.password' => ['required', 'string', 'min:8'],
        ];
    }
}
