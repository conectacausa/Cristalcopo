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
        Schema::create('vinculo_permissao_x_tela', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permissao_id')
                  ->constrained('gestao_permissao')
                  ->cascadeOnDelete();

            $table->foreignId('tela_id')
                  ->constrained('gestao_tela')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['permissao_id', 'tela_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculo_permissao_x_tela');
    }
};
