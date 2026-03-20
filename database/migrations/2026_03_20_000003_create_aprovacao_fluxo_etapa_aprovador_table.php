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
        Schema::create('aprovacao_fluxo_etapa_aprovador', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fluxo_etapa_id');
            $table->unsignedBigInteger('colaborador_id');

            $table->boolean('obrigatorio')->default(true);
            $table->integer('ordem_interna')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fluxo_etapa_id', 'fk_aprovacao_fluxo_etapa_aprovador_etapa')
                ->references('id')
                ->on('aprovacao_fluxo_etapa')
                ->onDelete('cascade');

            $table->foreign('colaborador_id', 'fk_aprovacao_fluxo_etapa_aprovador_colaborador')
                ->references('id')
                ->on('colaboradores')
                ->onDelete('restrict');

            $table->index('fluxo_etapa_id', 'idx_aprovacao_fluxo_etapa_aprovador_etapa');
            $table->index('colaborador_id', 'idx_aprovacao_fluxo_etapa_aprovador_colaborador');

            $table->unique(
                ['fluxo_etapa_id', 'colaborador_id'],
                'uq_aprovacao_fluxo_etapa_aprovador_etapa_colaborador'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_fluxo_etapa_aprovador', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_fluxo_etapa_aprovador_etapa');
            $table->dropForeign('fk_aprovacao_fluxo_etapa_aprovador_colaborador');
        });

        Schema::dropIfExists('aprovacao_fluxo_etapa_aprovador');
    }
};
