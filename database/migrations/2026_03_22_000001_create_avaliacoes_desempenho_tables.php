<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacao_desempenho_ciclos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->string('tipo_avaliacao', 20);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('status', 20)->default('rascunho');
            $table->string('forma_liberacao', 20)->default('manual');
            $table->boolean('permite_autoavaliacao')->default(true);
            $table->boolean('permite_avaliacao_gestor')->default(true);
            $table->boolean('permite_avaliacao_pares')->default(false);
            $table->boolean('permite_avaliacao_subordinados')->default(false);
            $table->boolean('anonimato')->default(false);
            $table->boolean('permite_edicao_ate_prazo_final')->default(false);
            $table->boolean('permite_resposta_parcial')->default(false);
            $table->boolean('lembrete_ativo')->default(false);
            $table->string('lembrete_frequencia', 30)->nullable();
            $table->unsignedInteger('lembrete_intervalo_dias')->nullable();
            $table->time('lembrete_horario')->nullable();
            $table->json('lembrete_canais')->nullable();
            $table->boolean('lembrete_parar_ao_responder')->default(true);
            $table->boolean('lembrete_final_antes_encerramento')->default(false);
            $table->string('publico_tipo', 30)->default('todos');
            $table->timestamps();
            $table->softDeletes();

            $table->index('nome');
            $table->index('tipo_avaliacao');
            $table->index('status');
            $table->index(['data_inicio', 'data_fim']);
        });

        Schema::create('avaliacao_desempenho_pilares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('avaliacao_desempenho_ciclos')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->decimal('peso', 5, 2)->default(0);
            $table->unsignedInteger('ordem')->default(1);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ciclo_id', 'ordem']);
            $table->index(['ciclo_id', 'ativo']);
        });

        Schema::create('avaliacao_desempenho_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilar_id')->constrained('avaliacao_desempenho_pilares')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pilar_id', 'ordem']);
            $table->index(['pilar_id', 'ativo']);
        });

        Schema::create('avaliacao_desempenho_subgrupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('avaliacao_desempenho_grupos')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->text('descricao')->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['grupo_id', 'ordem']);
            $table->index(['grupo_id', 'ativo']);
        });

        Schema::create('avaliacao_desempenho_perguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('avaliacao_desempenho_ciclos')->cascadeOnDelete();
            $table->foreignId('pilar_id')->nullable()->constrained('avaliacao_desempenho_pilares')->nullOnDelete();
            $table->foreignId('grupo_id')->nullable()->constrained('avaliacao_desempenho_grupos')->nullOnDelete();
            $table->foreignId('subgrupo_id')->nullable()->constrained('avaliacao_desempenho_subgrupos')->nullOnDelete();
            $table->text('enunciado');
            $table->text('descricao_apoio')->nullable();
            $table->string('tipo_resposta', 30);
            $table->boolean('obrigatoria')->default(true);
            $table->decimal('peso', 5, 2)->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->boolean('ativa')->default(true);
            $table->boolean('permite_comentario')->default(false);
            $table->boolean('comentario_obrigatorio')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ciclo_id', 'ativa']);
            $table->index(['grupo_id', 'subgrupo_id']);
            $table->index('tipo_resposta');
        });

        Schema::create('avaliacao_desempenho_pergunta_opcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pergunta_id')->constrained('avaliacao_desempenho_perguntas')->cascadeOnDelete();
            $table->string('texto', 255);
            $table->string('valor', 100)->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->boolean('ativa')->default(true);
            $table->timestamps();

            $table->index(['pergunta_id', 'ordem']);
        });

        Schema::create('avaliacao_desempenho_regras_aplicacao', function (Blueprint $table) {
            $table->id();
            $table->string('escopo_tipo', 20);
            $table->unsignedBigInteger('escopo_id');
            $table->string('regra_tipo', 20);
            $table->unsignedBigInteger('referencia_id');
            $table->timestamps();

            $table->unique(['escopo_tipo', 'escopo_id', 'regra_tipo', 'referencia_id'], 'ad_regra_unica');
            $table->index(['escopo_tipo', 'escopo_id'], 'ad_regra_escopo_idx');
            $table->index(['regra_tipo', 'referencia_id'], 'ad_regra_referencia_idx');
        });

        Schema::create('avaliacao_desempenho_publico_alvo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('avaliacao_desempenho_ciclos')->cascadeOnDelete();
            $table->string('tipo', 20);
            $table->unsignedBigInteger('referencia_id');
            $table->timestamps();

            $table->unique(['ciclo_id', 'tipo', 'referencia_id'], 'ad_publico_unico');
            $table->index(['ciclo_id', 'tipo'], 'ad_publico_ciclo_tipo_idx');
        });

        Schema::create('avaliacao_desempenho_avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('avaliacao_desempenho_ciclos')->cascadeOnDelete();
            $table->foreignId('colaborador_id')->constrained('colaboradores')->restrictOnDelete();
            $table->string('tipo_avaliacao', 20);
            $table->string('status', 20)->default('pendente');
            $table->timestamp('data_liberacao')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            $table->decimal('nota_final', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['ciclo_id', 'colaborador_id'], 'ad_avaliacao_unica');
            $table->index(['ciclo_id', 'status'], 'ad_avaliacao_ciclo_status_idx');
        });

        Schema::create('avaliacao_desempenho_avaliadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacao_desempenho_avaliacoes')->cascadeOnDelete();
            $table->foreignId('avaliador_id')->nullable()->constrained('colaboradores')->nullOnDelete();
            $table->string('papel', 20);
            $table->boolean('anonimo')->default(false);
            $table->string('status', 20)->default('pendente');
            $table->timestamp('prazo_resposta')->nullable();
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();

            $table->index(['avaliacao_id', 'papel'], 'ad_avaliadores_avaliacao_papel_idx');
            $table->index(['avaliador_id', 'status'], 'ad_avaliadores_colaborador_status_idx');
        });

        Schema::create('avaliacao_desempenho_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacao_desempenho_avaliacoes')->cascadeOnDelete();
            $table->foreignId('avaliador_vinculo_id')->constrained('avaliacao_desempenho_avaliadores')->cascadeOnDelete();
            $table->foreignId('pergunta_id')->constrained('avaliacao_desempenho_perguntas')->cascadeOnDelete();
            $table->decimal('resposta_numerica', 8, 2)->nullable();
            $table->boolean('resposta_boolean')->nullable();
            $table->string('resposta_opcao', 255)->nullable();
            $table->text('resposta_texto')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamp('respondida_em')->nullable();
            $table->timestamps();

            $table->unique(['avaliador_vinculo_id', 'pergunta_id'], 'ad_resposta_unica');
            $table->index(['avaliacao_id', 'pergunta_id'], 'ad_resposta_avaliacao_pergunta_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacao_desempenho_respostas');
        Schema::dropIfExists('avaliacao_desempenho_avaliadores');
        Schema::dropIfExists('avaliacao_desempenho_avaliacoes');
        Schema::dropIfExists('avaliacao_desempenho_publico_alvo');
        Schema::dropIfExists('avaliacao_desempenho_regras_aplicacao');
        Schema::dropIfExists('avaliacao_desempenho_pergunta_opcoes');
        Schema::dropIfExists('avaliacao_desempenho_perguntas');
        Schema::dropIfExists('avaliacao_desempenho_subgrupos');
        Schema::dropIfExists('avaliacao_desempenho_grupos');
        Schema::dropIfExists('avaliacao_desempenho_pilares');
        Schema::dropIfExists('avaliacao_desempenho_ciclos');
    }
};
