<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_contact_companies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('channel_contact_id')->index();
            $table->foreign('channel_contact_id')
                ->references('id')->on('channel_contacts')
                ->onDelete('cascade');

            $table->unsignedBigInteger('tenant_id')->index();
            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->onDelete('cascade');

            // Vínculo contato→Cliente POR TENANT. nullable: pode existir antes de
            // virar Cliente (lead ainda não cadastrado).
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->timestamp('last_interaction_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['channel_contact_id', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_contact_companies');
    }
};
