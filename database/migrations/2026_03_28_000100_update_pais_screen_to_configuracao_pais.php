<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $agora = now();

        $telaId = DB::table('gestao_tela')->where('id', 3)->value('id');

        if ($telaId) {
            DB::table('gestao_tela')
                ->where('id', 3)
                ->update([
                    'nome' => 'País',
                    'slug' => 'configuracao/pais',
                    'route_name' => 'configuracao.pais',
                    'updated_at' => $agora,
                ]);

            return;
        }

        DB::table('gestao_tela')->insert([
            'id' => 3,
            'modulo_id' => 2,
            'nome' => 'País',
            'slug' => 'configuracao/pais',
            'route_name' => 'configuracao.pais',
            'ordem' => 3,
            'created_at' => $agora,
            'updated_at' => $agora,
        ]);
    }

    public function down(): void
    {
        // Sem rollback destrutivo por não haver snapshot confiável dos valores anteriores.
    }
};
