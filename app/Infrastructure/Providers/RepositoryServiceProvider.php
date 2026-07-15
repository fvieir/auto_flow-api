<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Repositories\ClientAddressRepositoryInterface;
use App\Domain\Professional\Repositories\ScheduleBlockRepositoryInterface;
use App\Domain\Professional\Repositories\WorkScheduleRepositoryInterface;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;
use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use App\Domain\Whatsapp\Repositories\ChannelContactCompanyRepositoryInterface;
use App\Domain\Whatsapp\Repositories\ChannelContactRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaConversationRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaMessageRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WaPhoneNumberRepositoryInterface;
use App\Domain\Whatsapp\Repositories\WebhookEventRepositoryInterface;
use App\Infrastructure\Persistence\Appointment\Repositories\AppointmentRepository;
use App\Infrastructure\Persistence\Client\Repositories\ClientRepository;
use App\Infrastructure\Persistence\Client\Repositories\ClientAddressRepository;
use App\Infrastructure\Persistence\Professional\Repositories\ScheduleBlockRepository;
use App\Infrastructure\Persistence\Professional\Repositories\WorkScheduleRepository;
use App\Infrastructure\Persistence\Professional\Repositories\ProfessionalRepository;
use App\Infrastructure\Persistence\Service\Repositories\ServiceRepository;
use App\Infrastructure\Persistence\Tenant\Repositories\TenantRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\ChannelContactCompanyRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\ChannelContactRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\WaConversationRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\WaMessageRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\WaPhoneNumberRepository;
use App\Infrastructure\Persistence\Whatsapp\Repositories\WebhookEventRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Composition root: liga as interfaces do Domain às implementações da
 * Infrastructure e registra os singletons de context.
 */
final class RepositoryServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        TenantRepositoryInterface::class => TenantRepository::class,
        ProfessionalRepositoryInterface::class => ProfessionalRepository::class,
        ServiceRepositoryInterface::class => ServiceRepository::class,
        WorkScheduleRepositoryInterface::class => WorkScheduleRepository::class,
        ScheduleBlockRepositoryInterface::class => ScheduleBlockRepository::class,
        ClientRepositoryInterface::class => ClientRepository::class,
        ClientAddressRepositoryInterface::class => ClientAddressRepository::class,
        AppointmentRepositoryInterface::class => AppointmentRepository::class,
        WaPhoneNumberRepositoryInterface::class => WaPhoneNumberRepository::class,
        ChannelContactRepositoryInterface::class => ChannelContactRepository::class,
        ChannelContactCompanyRepositoryInterface::class => ChannelContactCompanyRepository::class,
        WaConversationRepositoryInterface::class => WaConversationRepository::class,
        WaMessageRepositoryInterface::class => WaMessageRepository::class,
        WebhookEventRepositoryInterface::class => WebhookEventRepository::class,
    ];

    public function register(): void
    {
        // Context de tenant vive durante todo o request/job.
        $this->app->singleton(CurrentTenant::class);
    }
}
