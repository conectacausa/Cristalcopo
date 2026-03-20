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
        Schema::create('aprovacao_solicitacao', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fluxo_id');

            // Ex: cargo, filial, colaborador
            $table->string('tipo_referencia', 100);

            // ID do registro real (ex: id do cargo)
            $table->unsignedBigInteger('referencia_id');

            $table->string('titulo', 255)->nullable();
            $table->text('descricao')->nullable();

            // pendente | em_aprovacao | aprovado | reprovado | cancelado
            $table->enum('status', [
                'pendente',
                'em_aprovacao',
                'aprovado',
                'reprovado',
                'cancelado'
            ])->default('pendente');

            // snapshot do modo do fluxo no momento da criação
            $table->enum('modo_aprovacao_snapshot', ['sequencial', 'paralelo']);

            $table->unsignedBigInteger('solicitante_colaborador_id');

            $table->integer('etapa_atual')->nullable();

            $table->timestamp('aberto_em')->nullable();
            $table->timestamp('finalizado_em')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fluxo_id', 'fk_aprovacao_solicitacao_fluxo')
                ->references('id')
                ->on('aprovacao_fluxo')
                ->onDelete('restrict');

            $table->foreign('solicitante_colaborador_id', 'fk_aprovacao_solicitacao_colaborador')
                ->references('id')
                ->on('colaboradores')
                ->onDelete('restrict');

            $table->index('tipo_referencia', 'idx_aprovacao_solicitacao_tipo_ref');
            $table->index('referencia_id', 'idx_aprovacao_solicitacao_ref_id');
            $table->index('status', 'idx_aprovacao_solicitacao_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_solicitacao', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_solicitacao_fluxo');
            $table->dropForeign('fk_aprovacao_solicitacao_colaborador');
        });

        Schema::dropIfExists('aprovacao_solicitacao');
    }
};
