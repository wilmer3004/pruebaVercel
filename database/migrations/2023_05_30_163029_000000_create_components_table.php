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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name', '150')->unique();
            $table->text('description');
            $table->unsignedBigInteger('component_type_id');
            $table->unsignedBigInteger('quarter_id');
            $table->integer('total_hours');
            $table->string('state','30')->default('activo');
            $table->timestamps();

            $table->foreign('component_type_id')
            ->references('id')
                ->on('components_type')
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
        Schema::dropIfExists('components');
    }
};
