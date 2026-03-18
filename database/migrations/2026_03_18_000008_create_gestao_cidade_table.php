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
        Schema::create('gestao_cidade', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->foreignId('estado_id')
                ->constrained('gestao_estado')
                ->cascadeOnDelete();
            $table->string('codigo_ibge', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('nome');
            $table->index('codigo_ibge');
            $table->unique(['estado_id', 'nome']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_cidade');
    }
};
