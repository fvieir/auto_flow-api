<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professional_service', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('professional_id')->index();
            $table->foreign('professional_id')
                ->references('id')->on('professionals')
                ->onDelete('cascade');

            $table->unsignedBigInteger('service_id')->index();
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['professional_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professional_service');
    }
};
