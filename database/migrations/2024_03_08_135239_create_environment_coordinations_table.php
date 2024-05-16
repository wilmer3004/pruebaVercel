<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('environment_coordinations', function (Blueprint $table) {
            $table->unsignedBigInteger('environment_id');
            $table->unsignedBigInteger('coordination_id');

            $table->foreign('environment_id')
                ->references('id')
                ->on('environments')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('coordination_id')
                ->references('id')
                ->on('coordinations')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environment_coordinations');
    }
};
