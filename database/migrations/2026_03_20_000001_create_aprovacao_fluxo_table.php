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
        Schema::create('aprovacao_fluxo', function (Blueprint $table) {
            $table->id();

            $table->string('nome_fluxo', 150);
            $table->string('slug', 160)->unique();
            $table->text('descricao')->nullable();

            // Ex.: cargo, filial, colaborador, movimentacao
            $table->string('tipo_referencia', 100);

            // sequencial | paralelo
            $table->enum('modo_aprovacao', ['sequencial', 'paralelo'])->default('sequencial');

            $table->boolean('permite_reprovacao')->default(true);
            $table->boolean('permite_retorno')->default(true);

            // ativo | inativo
            $table->enum('situacao', ['ativo', 'inativo'])->default('ativo');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('tipo_referencia', 'idx_aprovacao_fluxo_tipo_referencia');
            $table->index('situacao', 'idx_aprovacao_fluxo_situacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aprovacao_fluxo');
    }
};
