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
        Schema::create('year_quarters', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('quarter');
            $table->date('start_date');
            $table->date('finish_date');
            $table->string('state','30')->default('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('year_quarters');
    }
};
