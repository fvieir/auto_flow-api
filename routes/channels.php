<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tenant.{tenantId}.wa-conversations.{conversationId}', function ($user, int $tenantId, int $conversationId) {
    return $user->tenants()->where('tenants.id', $tenantId)->exists();
});
