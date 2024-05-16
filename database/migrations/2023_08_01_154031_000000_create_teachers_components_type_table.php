  
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
            Schema::create('teachers_components_type', function (Blueprint $table) {
                $table->unsignedBigInteger('teachers_id')->nullable();
                $table->unsignedBigInteger('components_type_id')->nullable();

                $table->foreign('teachers_id')
                    ->references('id')
                    ->on('teachers')
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
            Schema::dropIfExists('teachers_components_type');
        }
    };
