<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_contacts', function (Blueprint $table) {
            $table->id();

            // NÃO colocar client_id aqui: o vínculo contato→Cliente é POR TENANT
            // (cada tenant tem seu próprio Cliente para o mesmo telefone).
            // Ele vive em channel_contact_companies.client_id.

            $table->string('channel')->comment('whatsapp, instagram, webchat');
            $table->string('phone')->index();
            $table->string('external_id')->nullable()->comment('ex: whatsapp wa_id');
            $table->json('metadata')->nullable();
            $table->timestamp('last_interaction_at')->nullable();

            $table->timestamp('unlinked_at')->nullable()->index();
            $table->timestamps();

            // ATENÇÃO: no MySQL NULL é sempre distinto num índice único, então este
            // unique NÃO impede duas linhas ativas (unlinked_at = NULL) com o mesmo
            // channel+phone. A regra "só 1 contato ativo por channel+phone" precisa
            // ser garantida na camada de aplicação, dentro de transação.
            $table->unique(['channel', 'phone', 'unlinked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_contacts');
    }
};
