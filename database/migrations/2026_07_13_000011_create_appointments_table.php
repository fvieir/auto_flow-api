<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->unsignedBigInteger('client_id')->index();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();

            $table->unsignedBigInteger('professional_id')->index();
            $table->foreign('professional_id')->references('id')->on('professionals')->cascadeOnDelete();

            $table->unsignedBigInteger('service_id')->index();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();

            $table->dateTime('starts_at');
            $table->unsignedInteger('duration_minutes');
            $table->dateTime('ends_at');
            $table->string('status')->default('scheduled')->comment('scheduled|confirmed|completed|cancelled');
            $table->timestamps();

            $table->index(['professional_id', 'starts_at']);
            $table->index(['tenant_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
