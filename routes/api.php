<?php

use App\Presentation\Http\Controllers\Me\GetMeController;
use App\Presentation\Http\Controllers\Tenant\CreateTenantController;
use Illuminate\Support\Facades\Route;

Route::post('/tenants', CreateTenantController::class);

Route::middleware(['force.auth', 'resolve.tenant'])->group(function (): void {
    Route::get('/me', GetMeController::class);
});
