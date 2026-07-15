<?php

declare(strict_types=1);

namespace App\Presentation\Webhooks\WhatsApp\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Validação do webhook (GET com hub.challenge), exigida pela Meta ao
 * cadastrar a URL de callback:
 * https://developers.facebook.com/docs/graph-api/webhooks/getting-started#verification-requests
 *
 * O PHP substitui "." por "_" nos nomes de query params automaticamente
 * (comportamento nativo do parse_str) — por isso lemos hub_mode/
 * hub_verify_token/hub_challenge, mesmo a Meta enviando hub.mode/etc.
 */
final class VerifyWhatsAppWebhookController
{
    public function __invoke(Request $request): Response
    {
        $verifyToken = (string) config('services.whatsapp.verify_token');
        $providedToken = (string) $request->query('hub_verify_token', '');

        if (
            $request->query('hub_mode') === 'subscribe'
            && $verifyToken !== ''
            && hash_equals($verifyToken, $providedToken)
        ) {
            return response((string) $request->query('hub_challenge', ''), Response::HTTP_OK)
                ->header('Content-Type', 'text/plain');
        }

        return response('', Response::HTTP_FORBIDDEN);
    }
}
