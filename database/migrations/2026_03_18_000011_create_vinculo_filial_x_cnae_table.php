<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vinculo_filial_x_cnae', function (Blueprint $table) {
            $table->id();

            $table->foreignId('filial_id')
                ->constrained('empresa_filial')
                ->cascadeOnDelete();

            $table->foreignId('cnae_id')
                ->constrained('empresa_cnae')
                ->restrictOnDelete();

            $table->boolean('principal')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['filial_id', 'cnae_id'], 'uk_vinculo_filial_cnae');
            $table->index('filial_id');
            $table->index('cnae_id');
            $table->index('principal');
        });

        DB::statement("
            CREATE UNIQUE INDEX uk_vinculo_filial_principal
            ON vinculo_filial_x_cnae (filial_id)
            WHERE principal = true AND deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS uk_vinculo_filial_principal');

        Schema::dropIfExists('vinculo_filial_x_cnae');
    }
};
