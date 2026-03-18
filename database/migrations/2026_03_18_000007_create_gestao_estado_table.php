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
        Schema::create('gestao_estado', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('uf', 2);
            $table->foreignId('pais_id')
                ->constrained('gestao_pais')
                ->cascadeOnDelete();
            $table->string('codigo_ibge', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('nome');
            $table->index('uf');
            $table->index('codigo_ibge');
            $table->unique(['pais_id', 'uf']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_estado');
    }
};
