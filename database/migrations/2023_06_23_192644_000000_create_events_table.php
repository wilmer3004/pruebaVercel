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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('environment_id')->nullable();
            $table->unsignedBigInteger('component_id')->nullable();
            $table->unsignedBigInteger('study_sheet_id')->nullable();
            $table->string('study_sheet_state')->nullable();
            $table->string('environment_state')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->integer('total_hours');
            $table->unsignedBigInteger('teacher_id')->nullable();

            $table->foreign('environment_id')
                ->references('id')
                ->on('environments')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('component_id')
                ->references('id')
                ->on('components')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('study_sheet_id')
                ->references('id')
                ->on('study_sheets')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
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
        Schema::dropIfExists('events');
    }
};
