<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Infrastructure\Integrations\Agent\N8nAgentNotifier;
use App\Infrastructure\Integrations\WhatsApp\WhatsAppClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            WhatsAppClient::class,
            fn () => new WhatsAppClient(config('services.whatsapp.graph_api_version')),
        );

        $this->app->singleton(
            N8nAgentNotifier::class,
            fn () => new N8nAgentNotifier(
                config('services.n8n.webhook_url'),
                config('services.n8n.manager_webhook_url'),
            ),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
