<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garante que a requisição está autenticada via Sanctum (Bearer token) antes de
 * seguir. Lança AuthenticationException (renderizada como 401 JSON para /api/*).
 */
final class ForceAuthenticate
{
    public function __construct(private readonly AuthFactory $auth)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $guard = $this->auth->guard('sanctum');

        if ($guard->guest()) {
            throw new AuthenticationException('Unauthenticated.', ['sanctum']);
        }

        $this->auth->shouldUse('sanctum');

        return $next($request);
    }
}
