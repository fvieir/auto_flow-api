<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Professional;

use App\Domain\Professional\Entities\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read WorkSchedule $resource
 */
final class WorkScheduleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $workSchedule = $this->resource;

        return [
            'id' => $workSchedule->id(),
            'professional_id' => $workSchedule->professionalId(),
            'weekday' => $workSchedule->weekday(),
            'start_time' => $workSchedule->startTime(),
            'end_time' => $workSchedule->endTime(),
        ];
    }
}
