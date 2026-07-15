<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->unsignedBigInteger('channel_contact_id')->index();
            $table->foreign('channel_contact_id')
                ->references('id')->on('channel_contacts')
                ->onDelete('cascade');

            $table->unsignedBigInteger('wa_phone_number_id')->index();
            $table->foreign('wa_phone_number_id')
                ->references('id')->on('wa_phone_numbers')
                ->onDelete('cascade');

            $table->string('status')->default('open')->comment('open|resolved');
            $table->string('stage')->default('new')->comment(
                'new|in_progress|quote_sent|awaiting_date|converted|lost'
            );

            $table->unsignedBigInteger('last_attendant_id')->nullable();
            $table->foreign('last_attendant_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('resolved_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('pending_handoff_at')->nullable();
            $table->string('pending_handoff_subject')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_conversations');
    }
};
