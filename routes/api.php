<?php

use App\Presentation\Http\Controllers\Appointment\CancelAppointmentController;
use App\Presentation\Http\Controllers\Appointment\CreateAppointmentController;
use App\Presentation\Http\Controllers\Appointment\ListAppointmentsController;
use App\Presentation\Http\Controllers\Appointment\ListAvailabilityController;
use App\Presentation\Http\Controllers\Appointment\RescheduleAppointmentController;
use App\Presentation\Http\Controllers\Appointment\UpdateAppointmentStatusController;
use App\Presentation\Http\Controllers\Client\CreateClientController;
use App\Presentation\Http\Controllers\Client\CreateAddressController;
use App\Presentation\Http\Controllers\Client\DeleteClientController;
use App\Presentation\Http\Controllers\Client\DeleteAddressController;
use App\Presentation\Http\Controllers\Client\GetClientContextController;
use App\Presentation\Http\Controllers\Client\ListClientsController;
use App\Presentation\Http\Controllers\Client\ListAddressesController;
use App\Presentation\Http\Controllers\Client\SearchClientsController;
use App\Presentation\Http\Controllers\Client\SetPrimaryAddressController;
use App\Presentation\Http\Controllers\Client\UpdateClientController;
use App\Presentation\Http\Controllers\Client\UpdateAddressController;
use App\Presentation\Http\Controllers\Me\GetMeController;
use App\Presentation\Http\Controllers\Professional\CreateScheduleBlockController;
use App\Presentation\Http\Controllers\Professional\CreateWorkScheduleController;
use App\Presentation\Http\Controllers\Professional\CreateProfessionalController;
use App\Presentation\Http\Controllers\Professional\DeleteScheduleBlockController;
use App\Presentation\Http\Controllers\Professional\DeleteWorkScheduleController;
use App\Presentation\Http\Controllers\Professional\DeleteProfessionalController;
use App\Presentation\Http\Controllers\Professional\ListScheduleBlocksController;
use App\Presentation\Http\Controllers\Professional\ListWorkSchedulesController;
use App\Presentation\Http\Controllers\Professional\ListProfessionalsController;
use App\Presentation\Http\Controllers\Professional\UpdateScheduleBlockController;
use App\Presentation\Http\Controllers\Professional\UpdateWorkScheduleController;
use App\Presentation\Http\Controllers\Professional\UpdateProfessionalController;
use App\Presentation\Http\Controllers\Service\CreateServiceController;
use App\Presentation\Http\Controllers\Service\DeleteServiceController;
use App\Presentation\Http\Controllers\Service\ListServicesController;
use App\Presentation\Http\Controllers\Service\UpdateServiceController;
use App\Presentation\Http\Controllers\Tenant\CreateTenantController;
use App\Presentation\Http\Controllers\Whatsapp\ListConversationsController;
use App\Presentation\Http\Controllers\Whatsapp\ResolveWhatsAppContextController;
use App\Presentation\Http\Controllers\Whatsapp\SendWhatsAppMessageController;
use App\Presentation\Http\Controllers\Whatsapp\StartConversationController;
use App\Presentation\Http\Controllers\Whatsapp\UpdateConversationStageController;
use App\Presentation\Webhooks\WhatsApp\Controllers\ReceiveWhatsAppWebhookController;
use App\Presentation\Webhooks\WhatsApp\Controllers\VerifyWhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/tenants', CreateTenantController::class);

Route::get('/internal/wa/resolver', ResolveWhatsAppContextController::class);
Route::post('/internal/whatsapp/send', SendWhatsAppMessageController::class);

Route::get('/webhooks/wa', VerifyWhatsAppWebhookController::class);
Route::post('/webhooks/wa', ReceiveWhatsAppWebhookController::class)->middleware('verify.wa.signature');

Route::middleware(['force.auth', 'resolve.tenant'])->group(function (): void {
    Route::get('/me', GetMeController::class);

    Route::get('/professionals', ListProfessionalsController::class);
    Route::post('/professionals', CreateProfessionalController::class);
    Route::put('/professionals/{professional}', UpdateProfessionalController::class);
    Route::delete('/professionals/{professional}', DeleteProfessionalController::class);

    Route::get('/professionals/{professional}/work-schedules', ListWorkSchedulesController::class);
    Route::post('/professionals/{professional}/work-schedules', CreateWorkScheduleController::class);
    Route::put('/professionals/{professional}/work-schedules/{workSchedule}', UpdateWorkScheduleController::class);
    Route::delete('/professionals/{professional}/work-schedules/{workSchedule}', DeleteWorkScheduleController::class);

    Route::get('/professionals/{professional}/schedule-blocks', ListScheduleBlocksController::class);
    Route::post('/professionals/{professional}/schedule-blocks', CreateScheduleBlockController::class);
    Route::put('/professionals/{professional}/schedule-blocks/{scheduleBlock}', UpdateScheduleBlockController::class);
    Route::delete('/professionals/{professional}/schedule-blocks/{scheduleBlock}', DeleteScheduleBlockController::class);

    Route::get('/services', ListServicesController::class);
    Route::post('/services', CreateServiceController::class);
    Route::put('/services/{service}', UpdateServiceController::class);
    Route::delete('/services/{service}', DeleteServiceController::class);

    Route::get('/clients/search', SearchClientsController::class);
    Route::get('/clients', ListClientsController::class);
    Route::post('/clients', CreateClientController::class);
    Route::patch('/clients/{client}', UpdateClientController::class);
    Route::delete('/clients/{client}', DeleteClientController::class);

    Route::get('/clients/{client}/addresses', ListAddressesController::class);
    Route::post('/clients/{client}/addresses', CreateAddressController::class);
    Route::put('/clients/{client}/addresses/{address}', UpdateAddressController::class);
    Route::delete('/clients/{client}/addresses/{address}', DeleteAddressController::class);
    Route::patch('/clients/{client}/addresses/{address}/primary', SetPrimaryAddressController::class);
    Route::get('/clients/{client}/context', GetClientContextController::class);

    Route::get('/conversations', ListConversationsController::class);
    Route::post('/conversations', StartConversationController::class);
    Route::patch('/conversations/{conversation}/stage', UpdateConversationStageController::class);

    Route::get('/availability', ListAvailabilityController::class);

    Route::get('/appointments', ListAppointmentsController::class);
    Route::post('/appointments', CreateAppointmentController::class);
    Route::patch('/appointments/{appointment}/status', UpdateAppointmentStatusController::class);
    Route::patch('/appointments/{appointment}/cancel', CancelAppointmentController::class);
    Route::patch('/appointments/{appointment}/reschedule', RescheduleAppointmentController::class);
});
