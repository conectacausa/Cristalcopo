<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaborador_importacao_linhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('importacao_id')->constrained('colaborador_importacoes')->cascadeOnDelete();
            $table->unsignedInteger('linha');
            $table->string('matricula', 50)->nullable()->index();
            $table->foreignId('colaborador_id')->nullable()->constrained('colaboradores')->nullOnDelete();
            $table->string('acao', 30)->nullable(); // inserido, atualizado, ignorado, erro
            $table->json('dados_entrada')->nullable();
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->json('alteracoes')->nullable();
            $table->text('mensagem')->nullable();
            $table->boolean('sucesso')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaborador_importacao_linhas');
    }
};
