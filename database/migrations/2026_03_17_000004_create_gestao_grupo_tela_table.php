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
        Schema::create('gestao_grupo_tela', function (Blueprint $table) {
            $table->id();
            $table->string('nome_grupo', 150);
            $table->string('icone', 100)->nullable();

            $table->foreignId('modulo_id')
                  ->constrained('gestao_modulo')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->index('modulo_id');
            $table->index('nome_grupo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_grupo_tela');
    }
};
