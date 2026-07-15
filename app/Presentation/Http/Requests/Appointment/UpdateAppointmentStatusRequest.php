<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Appointment;

use App\Domain\Appointment\Enums\AppointmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateAppointmentStatusRequest extends FormRequest
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
            'status' => ['required', new Enum(AppointmentStatus::class)],
        ];
    }
}
