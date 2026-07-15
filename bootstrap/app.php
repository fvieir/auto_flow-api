<?php

use App\Domain\Shared\Exceptions\BusinessRuleException;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(
        channels: __DIR__.'/../routes/channels.php',
        attributes: ['middleware' => ['auth:sanctum']],
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'force.auth' => App\Presentation\Http\Middleware\ForceAuthenticate::class,
            'resolve.tenant' => App\Presentation\Http\Middleware\ResolveTenantMiddleware::class,
            'verify.wa.signature' => App\Presentation\Webhooks\WhatsApp\Middleware\VerifyWhatsAppSignature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Backend headless: sempre responder JSON (não há telas web / rota "login").
        $exceptions->shouldRenderJsonWhen(fn (Request $request) => true);

        $exceptions->render(fn (EntityNotFoundException $e) => response()->json(['message' => $e->getMessage()], 404));
        $exceptions->render(fn (BusinessRuleException $e) => response()->json(['message' => $e->getMessage()], 422));
    })->create();
