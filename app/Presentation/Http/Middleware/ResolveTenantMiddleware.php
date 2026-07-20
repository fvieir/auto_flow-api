<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Domain\Shared\ValueObjects\TenantId;
use App\Domain\Tenant\Repositories\TenantRepositoryInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolve o tenant atual. Dois caminhos:
 *  - caller de serviço (ForceAuthenticate marcou is_service_call): confia no
 *    header X-Tenant-Id diretamente, validando apenas que o tenant existe —
 *    não há usuário/vínculo em tenant_users nesse caso (ex.: N8N). Ver
 *    docs/n8n-regras-negocio.md.
 *  - usuário autenticado via Sanctum: se o header X-Tenant-Id for enviado e o
 *    usuário pertencer a ele, usa esse; senão, usa o primeiro vínculo em
 *    tenant_users.
 * Deve rodar depois do ForceAuthenticate. Ver docs/multitenancy.md.
 */
final class ResolveTenantMiddleware
{
    public function __construct(
        private readonly CurrentTenant $currentTenant,
        private readonly TenantRepositoryInterface $tenants,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->attributes->get('is_service_call') === true) {
            return $this->resolveForServiceCall($request, $next);
        }

        $user = $request->user();

        if ($user === null) {
            throw new AuthenticationException('Unauthenticated.');
        }

        /** @var list<int> $membershipIds */
        $membershipIds = $user->tenants()->pluck('tenants.id')->map(fn ($id) => (int) $id)->all();

        if ($membershipIds === []) {
            abort(Response::HTTP_FORBIDDEN, 'Usuário autenticado não pertence a nenhum tenant.');
        }

        $tenantId = $this->pickTenantId($request, $membershipIds);

        if ($tenantId === null) {
            abort(Response::HTTP_FORBIDDEN, 'Tenant solicitado não é acessível para este usuário.');
        }

        $this->currentTenant->set(new TenantId($tenantId));

        return $next($request);
    }

    private function resolveForServiceCall(Request $request, Closure $next): Response
    {
        $header = $request->header('X-Tenant-Id');

        if ($header === null || $header === '' || ! ctype_digit($header)) {
            abort(Response::HTTP_FORBIDDEN, 'Chamada de serviço exige o header X-Tenant-Id.');
        }

        $tenantId = new TenantId((int) $header);

        if ($this->tenants->findById($tenantId) === null) {
            abort(Response::HTTP_FORBIDDEN, 'Tenant informado não existe.');
        }

        $this->currentTenant->set($tenantId);

        return $next($request);
    }

    /**
     * @param  list<int>  $membershipIds
     */
    private function pickTenantId(Request $request, array $membershipIds): ?int
    {
        $header = $request->header('X-Tenant-Id');

        if ($header !== null && $header !== '') {
            $requested = (int) $header;

            return in_array($requested, $membershipIds, true) ? $requested : null;
        }

        return $membershipIds[0];
    }
}
