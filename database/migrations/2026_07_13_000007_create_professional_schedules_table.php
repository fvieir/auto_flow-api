<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();

            $table->unsignedBigInteger('professional_id')->index();
            $table->foreign('professional_id')
                ->references('id')->on('professionals')
                ->onDelete('cascade');

            $table->unsignedTinyInteger('weekday')->comment('0=Sunday .. 6=Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['professional_id', 'weekday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_schedules');
    }
};
