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
        Schema::create('aprovacao_configuracoes', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_referencia', 100)->unique();
            $table->unsignedBigInteger('fluxo_id');
            $table->boolean('ativo')->default(true);

            $table->timestamps();

            $table->foreign('fluxo_id', 'fk_aprovacao_configuracoes_fluxo')
                ->references('id')
                ->on('aprovacao_fluxo')
                ->onDelete('restrict');

            $table->index('fluxo_id', 'idx_aprovacao_configuracoes_fluxo_id');
            $table->index('ativo', 'idx_aprovacao_configuracoes_ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_configuracoes', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_configuracoes_fluxo');
        });

        Schema::dropIfExists('aprovacao_configuracoes');
    }
};
