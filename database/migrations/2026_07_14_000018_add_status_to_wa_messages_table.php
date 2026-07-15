<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wa_messages', function (Blueprint $table) {
            $table->string('status')->nullable()->after('sender_id')->comment('sent|delivered|read|failed');
            $table->timestamp('status_updated_at')->nullable()->after('status');
            $table->text('status_error')->nullable()->after('status_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('wa_messages', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_updated_at', 'status_error']);
        });
    }
};
