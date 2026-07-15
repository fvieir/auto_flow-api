<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Appointment;

use App\Application\Appointment\Support\TenantClock;
use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Appointment $resource
 */
final class AppointmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $appointment = $this->resource;
        $timezone = TenantModel::find(app(CurrentTenant::class)->id()->value())->timezone;

        return [
            'id' => $appointment->id(),
            'client_id' => $appointment->clientId(),
            'professional_id' => $appointment->professionalId(),
            'service_id' => $appointment->serviceId(),
            'starts_at' => TenantClock::formatLocal($appointment->startsAt(), $timezone),
            'ends_at' => TenantClock::formatLocal($appointment->endsAt(), $timezone),
            'duration_minutes' => $appointment->durationMinutes(),
            'status' => $appointment->status()->value,
        ];
    }
}
