<?php

declare(strict_types=1);

namespace App\Presentation\Webhooks\WhatsApp\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Valida o header X-Hub-Signature-256 do webhook da Meta (HMAC SHA256 do
 * corpo bruto da requisição, assinado com o App Secret do app Meta).
 * https://developers.facebook.com/docs/graph-api/webhooks/getting-started#verify-payloads
 */
final class VerifyWhatsAppSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $appSecret = (string) config('services.whatsapp.app_secret');
        $signatureHeader = (string) $request->header('X-Hub-Signature-256', '');

        $expected = 'sha256='.hash_hmac('sha256', $request->getContent(), $appSecret);

        if ($appSecret === '' || ! hash_equals($expected, $signatureHeader)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Assinatura do webhook inválida.');
        }

        return $next($request);
    }
}
