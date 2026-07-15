<?php

declare(strict_types=1);

namespace App\Application\Appointment\Support;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

final class TenantClock
{
    public static function parseLocalDateTime(string $startsAt, string $timezone): DateTimeImmutable
    {
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d H:i', $startsAt, new DateTimeZone($timezone));

        if ($parsed === false) {
            throw new InvalidArgumentException("Formato de starts_at inválido: {$startsAt}");
        }

        return $parsed->setTimezone(new DateTimeZone('UTC'));
    }

    public static function parseLocalDate(string $data, string $timezone): DateTimeImmutable
    {
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "{$data} 00:00:00", new DateTimeZone($timezone));

        if ($parsed === false) {
            throw new InvalidArgumentException("Formato de data inválido: {$data}");
        }

        return $parsed;
    }

    public static function formatLocal(DateTimeImmutable $instant, string $timezone): string
    {
        return $instant->setTimezone(new DateTimeZone($timezone))->format('Y-m-d H:i:s');
    }
}
