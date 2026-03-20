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
        Schema::create('aprovacao_log', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitacao_id');
            $table->unsignedBigInteger('solicitacao_etapa_id')->nullable();
            $table->unsignedBigInteger('colaborador_id')->nullable();

            $table->string('evento', 50);
            $table->text('descricao')->nullable();

            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('cookie_hash', 255)->nullable();

            $table->json('payload_json')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('solicitacao_id', 'fk_aprovacao_log_solicitacao')
                ->references('id')
                ->on('aprovacao_solicitacao')
                ->onDelete('cascade');

            $table->foreign('solicitacao_etapa_id', 'fk_aprovacao_log_solicitacao_etapa')
                ->references('id')
                ->on('aprovacao_solicitacao_etapa')
                ->onDelete('set null');

            $table->foreign('colaborador_id', 'fk_aprovacao_log_colaborador')
                ->references('id')
                ->on('colaboradores')
                ->onDelete('set null');

            $table->index('solicitacao_id', 'idx_aprovacao_log_solicitacao');
            $table->index('solicitacao_etapa_id', 'idx_aprovacao_log_solicitacao_etapa');
            $table->index('colaborador_id', 'idx_aprovacao_log_colaborador');
            $table->index('evento', 'idx_aprovacao_log_evento');
            $table->index('created_at', 'idx_aprovacao_log_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aprovacao_log', function (Blueprint $table) {
            $table->dropForeign('fk_aprovacao_log_solicitacao');
            $table->dropForeign('fk_aprovacao_log_solicitacao_etapa');
            $table->dropForeign('fk_aprovacao_log_colaborador');
        });

        Schema::dropIfExists('aprovacao_log');
    }
};
