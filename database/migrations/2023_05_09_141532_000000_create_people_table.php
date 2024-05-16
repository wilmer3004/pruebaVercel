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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name', '45');
            $table->string('lastname', '45');
            $table->bigInteger('phone');
            $table->bigInteger('document')->unique();
            $table->string('email', '50')->unique();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('document_type_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('document_type_id')
                ->references('id')
                ->on('documents_type')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
