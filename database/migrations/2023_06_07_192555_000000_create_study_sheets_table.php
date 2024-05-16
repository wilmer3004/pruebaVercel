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
        Schema::create('study_sheets', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->integer('num')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->integer('num_trainnies');
            $table->unsignedBigInteger('day_id')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->unsignedBigInteger('quarter_id')->nullable();
            $table->date('start_lective');
            $table->date('end_lective')->nullable();
            $table->enum('state', ['activo', 'inactivo', 'inactivo_union'])->default('activo');
            $table->timestamps();

            $table->foreign('program_id')
                ->references('id')
                ->on('programs')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('day_id')
                ->references('id')
                ->on('days')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('quarter_id')
                ->references('id')
                ->on('quarters')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_sheets');
    }
};
