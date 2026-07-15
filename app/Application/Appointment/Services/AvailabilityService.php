<?php

declare(strict_types=1);

namespace App\Application\Appointment\Services;

use App\Domain\Appointment\Exceptions\TimeSlotUnavailableException;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

final class AvailabilityService
{
    public function __construct(
        private readonly WorkScheduleRepositoryInterface $workSchedules,
        private readonly ScheduleBlockRepositoryInterface $scheduleBlocks,
        private readonly AppointmentRepositoryInterface $appointments,
    ) {
    }

    public function ensureSlotAvailable(
        int $professionalId,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        string $timezone,
        ?int $excludeAppointmentId = null,
    ): void {
        if (! $this->isWithinWorkingHours($professionalId, $start, $end, $timezone)) {
            throw new TimeSlotUnavailableException();
        }

        if ($this->conflictsWithBlock($professionalId, $start, $end)) {
            throw new TimeSlotUnavailableException();
        }

        if ($this->appointments->overlapsExisting($professionalId, $start, $end, $excludeAppointmentId)) {
            throw new TimeSlotUnavailableException();
        }
    }

    /**
     * @return list<array{start: DateTimeImmutable, end: DateTimeImmutable}>
     */
    public function listFreeSlots(
        int $professionalId,
        DateTimeImmutable $localDay,
        int $durationMinutes,
        string $timezone,
    ): array {
        $tz = new DateTimeZone($timezone);
        $utc = new DateTimeZone('UTC');
        $dateStr = $localDay->format('Y-m-d');
        $weekday = (int) (new DateTimeImmutable($dateStr, $tz))->format('w');

        $dayWorkSchedules = array_filter(
            $this->workSchedules->listByProfessional($professionalId),
            fn ($workSchedule) => $workSchedule->weekday() === $weekday,
        );

        if ($dayWorkSchedules === []) {
            return [];
        }

        $dayStartUtc = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "{$dateStr} 00:00:00", $tz)->setTimezone($utc);
        $dayEndUtc = $dayStartUtc->add(new DateInterval('P1D'));

        $busySlots = $this->busyIntervals($professionalId, $dayStartUtc, $dayEndUtc);

        $slots = [];
        $interval = new DateInterval("PT{$durationMinutes}M");

        foreach ($dayWorkSchedules as $workSchedule) {
            $slotStart = DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                "{$dateStr} {$workSchedule->startTime()}:00",
                $tz,
            )->setTimezone($utc);

            $windowEnd = DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                "{$dateStr} {$workSchedule->endTime()}:00",
                $tz,
            )->setTimezone($utc);

            while (true) {
                $slotEnd = $slotStart->add($interval);

                if ($slotEnd > $windowEnd) {
                    break;
                }

                if (! $this->overlapsAny($slotStart, $slotEnd, $busySlots)) {
                    $slots[] = ['start' => $slotStart, 'end' => $slotEnd];
                }

                $slotStart = $slotEnd;
            }
        }

        return $slots;
    }

    private function isWithinWorkingHours(
        int $professionalId,
        DateTimeImmutable $start,
        DateTimeImmutable $end,
        string $timezone,
    ): bool {
        $tz = new DateTimeZone($timezone);
        $utc = new DateTimeZone('UTC');
        $localStart = $start->setTimezone($tz);
        $weekday = (int) $localStart->format('w');
        $dateStr = $localStart->format('Y-m-d');

        foreach ($this->workSchedules->listByProfessional($professionalId) as $workSchedule) {
            if ($workSchedule->weekday() !== $weekday) {
                continue;
            }

            $windowStart = DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                "{$dateStr} {$workSchedule->startTime()}:00",
                $tz,
            )->setTimezone($utc);

            $windowEnd = DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                "{$dateStr} {$workSchedule->endTime()}:00",
                $tz,
            )->setTimezone($utc);

            if ($start >= $windowStart && $end <= $windowEnd) {
                return true;
            }
        }

        return false;
    }

    private function conflictsWithBlock(int $professionalId, DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        foreach ($this->scheduleBlocks->listByProfessional($professionalId) as $scheduleBlock) {
            if ($start < $scheduleBlock->endAt() && $scheduleBlock->startAt() < $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<array{start: DateTimeImmutable, end: DateTimeImmutable}>
     */
    private function busyIntervals(int $professionalId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $scheduleBlocks = array_map(
            fn ($scheduleBlock) => ['start' => $scheduleBlock->startAt(), 'end' => $scheduleBlock->endAt()],
            array_filter(
                $this->scheduleBlocks->listByProfessional($professionalId),
                fn ($scheduleBlock) => $start < $scheduleBlock->endAt() && $scheduleBlock->startAt() < $end,
            ),
        );

        $appointments = $this->appointments->busyIntervalsFor($professionalId, $start, $end);

        return [...array_values($scheduleBlocks), ...$appointments];
    }

    /**
     * @param  list<array{start: DateTimeImmutable, end: DateTimeImmutable}>  $busySlots
     */
    private function overlapsAny(DateTimeImmutable $start, DateTimeImmutable $end, array $busySlots): bool
    {
        foreach ($busySlots as $busySlot) {
            if ($start < $busySlot['end'] && $busySlot['start'] < $end) {
                return true;
            }
        }

        return false;
    }
}
