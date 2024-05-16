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
        Schema::create('components_programs', function (Blueprint $table) {
            $table->unsignedBigInteger('component_id');
            $table->unsignedBigInteger('program_id');

            $table->foreign('component_id')
                ->references('id')
                ->on('components')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('program_id')
                ->references('id')
                ->on('programs')
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
        Schema::dropIfExists('components_programs');
    }
};
