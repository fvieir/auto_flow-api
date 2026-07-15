<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
