<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garante que a requisição está autenticada antes de seguir — via Sanctum
 * (Bearer token de usuário) ou via token de serviço fixo (caller interno de
 * confiança, ex.: N8N). Lança AuthenticationException (renderizada como 401
 * JSON para /api/*). Ver docs/n8n-regras-negocio.md sobre o token de serviço.
 */
final class ForceAuthenticate
{
    public function __construct(private readonly AuthFactory $auth)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->hasValidServiceToken($request)) {
            $request->attributes->set('is_service_call', true);

            return $next($request);
        }

        $guard = $this->auth->guard('sanctum');

        if ($guard->guest()) {
            throw new AuthenticationException('Unauthenticated.', ['sanctum']);
        }

        $this->auth->shouldUse('sanctum');

        return $next($request);
    }

    private function hasValidServiceToken(Request $request): bool
    {
        $expected = config('services.n8n.service_token');

        if (! is_string($expected) || $expected === '') {
            return false;
        }

        $provided = $request->bearerToken();

        return $provided !== null && hash_equals($expected, $provided);
    }
}
