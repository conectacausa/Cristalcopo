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
        Schema::create('aprovacao_fluxo_etapa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fluxo_id');

            $table->string('nome_etapa', 150);
            $table->integer('ordem');

            // unanimidade | qualquer_um | maioria
            $table->enum('tipo_aprovacao_etapa', ['unanimidade', 'qualquer_um', 'maioria'])->default('unanimidade');

            // usado quando a regra precisar de quantidade mínima
            $table->integer('quantidade_minima_aprovacao')->nullable();

            // ativo | inativo
            $table->enum('situacao', ['ativo', 'inativo'])->default('ativo');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fluxo_id', 'fk_aprovacao_fluxo_etapa_fluxo')
                ->references('id')
                ->on('aprovacao_fluxo')
                ->onDelete('cascade');

            $table->index('fluxo_id', 'idx_aprovacao_fluxo_etapa_fluxo_id');
            $table->index('ordem', 'idx_aprovacao_fluxo_etapa_ordem');
            $table->index('situacao', 'idx_aprovacao_fluxo_etapa_situacao');

            $table->unique(['fluxo_id', 'ordem'], 'uq_aprovacao_fluxo_etapa_fluxo_ordem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_fluxo_etapa', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_fluxo_etapa_fluxo');
        });

        Schema::dropIfExists('aprovacao_fluxo_etapa');
    }
};
