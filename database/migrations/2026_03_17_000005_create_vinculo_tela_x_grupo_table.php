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
        Schema::create('vinculo_tela_x_grupo', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tela_id')
                  ->constrained('gestao_tela')
                  ->cascadeOnDelete();

            $table->foreignId('grupo_id')
                  ->constrained('gestao_grupo_tela')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['tela_id', 'grupo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculo_tela_x_grupo');
    }
};
