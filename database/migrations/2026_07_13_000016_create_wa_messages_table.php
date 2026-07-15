<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('conversation_id')->index();
            $table->foreign('conversation_id')
                ->references('id')->on('wa_conversations')
                ->onDelete('cascade');

            $table->string('wamid')->nullable()->index();
            $table->string('direction')->comment('inbound|outbound');
            $table->string('type');
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->string('context_wamid')->nullable()->comment('wamid da mensagem respondida');

            $table->string('sender_type')->comment('contact|agent|employee|system');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_messages');
    }
};
