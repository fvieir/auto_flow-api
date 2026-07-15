<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Appointment;

use App\Domain\Shared\Tenancy\CurrentTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateAppointmentRequest extends FormRequest
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
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'professional_id' => [
                'required',
                'integer',
                Rule::exists('professionals', 'id')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'starts_at' => ['required', 'date_format:Y-m-d H:i'],
        ];
    }
}
