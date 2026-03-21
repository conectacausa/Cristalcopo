<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos_escolaridades', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 150);
            $table->integer('ordem')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('descricao');
            $table->index('ordem');
        });

        DB::table('cargos_escolaridades')->insert([
            ['descricao' => 'Ensino Fundamental Incompleto', 'ordem' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Fundamental Completo', 'ordem' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Médio Incompleto', 'ordem' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Médio Completo', 'ordem' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Técnico Incompleto', 'ordem' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Técnico Completo', 'ordem' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Superior Incompleto', 'ordem' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Ensino Superior Completo', 'ordem' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Pós-graduação', 'ordem' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'MBA', 'ordem' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Mestrado', 'ordem' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['descricao' => 'Doutorado', 'ordem' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos_escolaridades');
    }
};
