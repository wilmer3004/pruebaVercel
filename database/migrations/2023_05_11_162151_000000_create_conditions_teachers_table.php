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
        Schema::create('conditions_teacher', function (Blueprint $table) {
            $table->unsignedBigInteger('condition_id');
            $table->unsignedBigInteger('teacher_id');

            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conditions_teacher');
    }
};
