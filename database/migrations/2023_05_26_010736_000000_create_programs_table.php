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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', '100')->unique();
            $table->text('description');
            $table->unsignedBigInteger('coordination_id');
            $table->unsignedBigInteger('program_type_id');
            $table->unsignedBigInteger('duration');
            $table->string('color','60')->unique();
            $table->string('state','30')->default('activo');


            $table->foreign('coordination_id')
                ->references('id')
                ->on('coordinations')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('program_type_id')
                ->references('id')
                ->on('program_type')
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
        Schema::dropIfExists('programs');
    }
};
