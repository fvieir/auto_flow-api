<?php

declare(strict_types=1);

namespace App\Application\Client\UseCases;

use App\Domain\Appointment\Entities\Appointment;
use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Exceptions\ClientNotFoundException;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;

final class GetClientContextUseCase
{
    public function __construct(
        private readonly ClientRepositoryInterface $clients,
        private readonly AppointmentRepositoryInterface $appointments,
    ) {
    }

    /**
     * @return array{client: Client, upcomingAppointment: ?Appointment}
     */
    public function handle(int $clientId): array
    {
        $client = $this->clients->findById($clientId);

        if ($client === null) {
            throw new ClientNotFoundException($clientId);
        }

        $upcoming = $this->appointments->upcomingByClient(
            $clientId,
            new DateTimeImmutable('now', new DateTimeZone('UTC')),
            1,
        );

        return [
            'client' => $client,
            'upcomingAppointment' => $upcoming[0] ?? null,
        ];
    }
}
