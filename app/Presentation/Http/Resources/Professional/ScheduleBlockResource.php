<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources\Professional;

use App\Domain\Professional\Entities\ScheduleBlock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read ScheduleBlock $resource
 */
final class ScheduleBlockResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $scheduleBlock = $this->resource;

        return [
            'id' => $scheduleBlock->id(),
            'professional_id' => $scheduleBlock->professionalId(),
            'start_at' => $scheduleBlock->startAt()->format(DATE_ATOM),
            'end_at' => $scheduleBlock->endAt()->format(DATE_ATOM),
            'reason' => $scheduleBlock->reason(),
        ];
    }
}
