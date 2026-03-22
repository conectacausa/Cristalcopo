<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $moduloId = DB::table('gestao_modulo')
            ->where('nome_modulo', 'Avaliações')
            ->value('id');

        if (! $moduloId) {
            $moduloId = DB::table('gestao_modulo')->insertGetId([
                'nome_modulo' => 'Avaliações',
                'ordem' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $grupoId = DB::table('gestao_grupo_tela')
            ->where('modulo_id', $moduloId)
            ->where('nome_grupo', 'Avaliação de Desempenho')
            ->value('id');

        if (! $grupoId) {
            $grupoId = DB::table('gestao_grupo_tela')->insertGetId([
                'nome_grupo' => 'Avaliação de Desempenho',
                'icone' => 'clipboard',
                'modulo_id' => $moduloId,
                'ordem' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $telaId = DB::table('gestao_tela')
            ->where('slug', 'avaliacoes/desempenho/ciclos')
            ->value('id');

        if (! $telaId) {
            $telaId = DB::table('gestao_tela')->insertGetId([
                'nome_tela' => 'Ciclos de Desempenho',
                'icone' => 'clipboard',
                'slug' => 'avaliacoes/desempenho/ciclos',
                'modulo_id' => $moduloId,
                'ordem' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $vinculoExiste = DB::table('vinculo_tela_x_grupo')
            ->where('tela_id', $telaId)
            ->where('grupo_id', $grupoId)
            ->exists();

        if (! $vinculoExiste) {
            DB::table('vinculo_tela_x_grupo')->insert([
                'tela_id' => $telaId,
                'grupo_id' => $grupoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $telaId = DB::table('gestao_tela')->where('slug', 'avaliacoes/desempenho/ciclos')->value('id');

        if ($telaId) {
            DB::table('vinculo_tela_x_grupo')->where('tela_id', $telaId)->delete();
            DB::table('vinculo_permissao_x_tela')->where('tela_id', $telaId)->delete();
            DB::table('gestao_tela')->where('id', $telaId)->delete();
        }

        $grupoId = DB::table('gestao_grupo_tela')
            ->where('nome_grupo', 'Avaliação de Desempenho')
            ->value('id');

        if ($grupoId) {
            DB::table('gestao_grupo_tela')->where('id', $grupoId)->delete();
        }

        $moduloId = DB::table('gestao_modulo')->where('nome_modulo', 'Avaliações')->value('id');

        if ($moduloId) {
            $possuiTelas = DB::table('gestao_tela')->where('modulo_id', $moduloId)->exists();
            $possuiGrupos = DB::table('gestao_grupo_tela')->where('modulo_id', $moduloId)->exists();

            if (! $possuiTelas && ! $possuiGrupos) {
                DB::table('gestao_modulo')->where('id', $moduloId)->delete();
            }
        }
    }
};
