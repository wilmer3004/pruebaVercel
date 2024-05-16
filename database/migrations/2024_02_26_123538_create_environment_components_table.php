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
        Schema::create('environment_components', function (Blueprint $table) {
            $table->unsignedBigInteger('environment_id');
            $table->unsignedBigInteger('component_type_id');
            $table->timestamps();

            $table->foreign('environment_id')
                ->references('id')
                ->on('environments')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('component_type_id')
                ->references('id')
                ->on('components_type')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environment_components');
    }
};