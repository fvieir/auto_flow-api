<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Me;

use App\Domain\Shared\Tenancy\CurrentTenant;
use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * GET /me — retorna o usuário autenticado + o tenant resolvido no context
 * (via ResolveTenantMiddleware) + o papel do usuário nesse tenant.
 */
final class GetMeController
{
    public function __invoke(Request $request, CurrentTenant $currentTenant): JsonResponse
    {
        $user = $request->user();
        $tenantId = $currentTenant->id()->value();

        /** @var TenantModel $tenant */
        $tenant = TenantModel::findOrFail($tenantId);

        $role = $user->tenants()
            ->where('tenants.id', $tenantId)
            ->first()?->getRelationValue('pivot')?->role;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'tenant' => [
                'id' => (int) $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'status' => $tenant->status,
                'timezone' => $tenant->timezone,
            ],
            'role' => $role,
        ]);
    }
}
