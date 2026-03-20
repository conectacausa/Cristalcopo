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
        Schema::create('aprovacao_solicitacao_etapa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitacao_id');
            $table->unsignedBigInteger('fluxo_etapa_id')->nullable();

            $table->string('nome_etapa_snapshot', 150);
            $table->integer('ordem');

            $table->enum('tipo_aprovacao_snapshot', [
                'unanimidade',
                'qualquer_um',
                'maioria'
            ])->default('unanimidade');

            $table->integer('quantidade_minima_aprovacao_snapshot')->nullable();

            // aguardando | liberada | aprovada | reprovada | cancelada
            $table->enum('status', [
                'aguardando',
                'liberada',
                'aprovada',
                'reprovada',
                'cancelada'
            ])->default('aguardando');

            $table->timestamp('liberada_em')->nullable();
            $table->timestamp('finalizada_em')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('solicitacao_id', 'fk_aprovacao_solicitacao_etapa_solicitacao')
                ->references('id')
                ->on('aprovacao_solicitacao')
                ->onDelete('cascade');

            $table->foreign('fluxo_etapa_id', 'fk_aprovacao_solicitacao_etapa_fluxo_etapa')
                ->references('id')
                ->on('aprovacao_fluxo_etapa')
                ->onDelete('set null');

            $table->index('solicitacao_id', 'idx_aprovacao_solicitacao_etapa_solicitacao');
            $table->index('ordem', 'idx_aprovacao_solicitacao_etapa_ordem');
            $table->index('status', 'idx_aprovacao_solicitacao_etapa_status');

            $table->unique(
                ['solicitacao_id', 'ordem'],
                'uq_aprovacao_solicitacao_etapa_solicitacao_ordem'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_solicitacao_etapa', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_solicitacao_etapa_solicitacao');
            $table->dropForeign('fk_aprovacao_solicitacao_etapa_fluxo_etapa');
        });

        Schema::dropIfExists('aprovacao_solicitacao_etapa');
    }
};
