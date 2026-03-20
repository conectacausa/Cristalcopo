<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aprovacao_configuracoes', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_referencia'); // ex: cargo, filial
            $table->foreignId('fluxo_id')->constrained('aprovacao_fluxos');

            $table->boolean('ativo')->default(true);

            $table->timestamps();

            $table->unique(['tipo_referencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aprovacao_configuracoes');
    }
};
