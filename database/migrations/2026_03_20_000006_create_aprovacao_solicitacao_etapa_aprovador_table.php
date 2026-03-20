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
        Schema::create('aprovacao_solicitacao_etapa_aprovador', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitacao_etapa_id');
            $table->unsignedBigInteger('colaborador_id');

            $table->string('nome_aprovador_snapshot', 150)->nullable();

            // pendente | aprovado | reprovado | cancelado
            $table->enum('status', [
                'pendente',
                'aprovado',
                'reprovado',
                'cancelado'
            ])->default('pendente');

            $table->timestamp('decisao_em')->nullable();
            $table->text('comentario')->nullable();

            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('cookie_hash', 255)->nullable();

            $table->timestamp('permitiu_reversao_ate')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('solicitacao_etapa_id', 'fk_aprov_sol_etapa_aprovador_etapa')
                ->references('id')
                ->on('aprovacao_solicitacao_etapa')
                ->onDelete('cascade');

            $table->foreign('colaborador_id', 'fk_aprov_sol_etapa_aprovador_colaborador')
                ->references('id')
                ->on('colaboradores')
                ->onDelete('restrict');

            $table->index('solicitacao_etapa_id', 'idx_aprov_sol_etapa_aprovador_etapa');
            $table->index('colaborador_id', 'idx_aprov_sol_etapa_aprovador_colaborador');
            $table->index('status', 'idx_aprov_sol_etapa_aprovador_status');

            $table->unique(
                ['solicitacao_etapa_id', 'colaborador_id'],
                'uq_aprov_sol_etapa_aprovador_etapa_colaborador'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_solicitacao_etapa_aprovador', function (Blueprint $table) {
            $table->dropForeign('fk_aprov_sol_etapa_aprovador_etapa');
            $table->dropForeign('fk_aprov_sol_etapa_aprovador_colaborador');
        });

        Schema::dropIfExists('aprovacao_solicitacao_etapa_aprovador');
    }
};
