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
        Schema::create('environments', function (Blueprint $table) {
            $table->id();
            $table->string('name', '45')->unique();
            $table->unsignedBigInteger('headquarter_id')->nullable();
            $table->integer('floor');
            $table->integer('capacity');
            $table->unsignedBigInteger('components_type_id')->nullable();
            $table->string('state','30')->default('activo');
            $table->timestamps();

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('components_type_id')
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
        Schema::dropIfExists('environments');
    }
};
