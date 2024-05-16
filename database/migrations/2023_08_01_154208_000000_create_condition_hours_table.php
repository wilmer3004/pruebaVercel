
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
            Schema::create('conditions_hours', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id');
                $table->unsignedBigInteger('condition_id');
                $table->integer('percentage');
                $table->boolean('state')->default(true);

                $table->foreign('contract_id')
                    ->references('id')
                    ->on('contracts')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');

                $table->foreign('condition_id')
                    ->references('id')
                    ->on('conditions')
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
            Schema::dropIfExists('conditions_hours');
        }
    };
