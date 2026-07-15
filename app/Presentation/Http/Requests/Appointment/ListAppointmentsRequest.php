<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Appointment;

use App\Domain\Appointment\Enums\AppointmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class ListAppointmentsRequest extends FormRequest
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
            'status' => ['sometimes', new Enum(AppointmentStatus::class)],
            'professional_id' => ['sometimes', 'integer'],
            'client_id' => ['sometimes', 'integer'],
            'data' => ['sometimes', 'date_format:Y-m-d'],
        ];
    }
}
