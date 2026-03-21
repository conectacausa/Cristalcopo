<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->string('matricula', 50)->nullable()->after('nome_completo')->index();
            $table->string('codigo_importacao', 100)->nullable()->after('matricula')->index();
            $table->string('nome_social', 200)->nullable()->after('nome_completo');

            $table->string('regime', 50)->nullable()->after('foto');
            $table->string('forma_trabalho', 50)->nullable()->after('regime');

            $table->foreignId('cargo_id')->nullable()->after('permissao_id')->constrained('cargos')->nullOnDelete();
            $table->foreignId('filial_id')->nullable()->after('cargo_id')->constrained('empresa_filial')->nullOnDelete();
            $table->foreignId('setor_id')->nullable()->after('filial_id')->constrained('empresa_setores')->nullOnDelete();
            $table->foreignId('superior_imediato_id')->nullable()->after('setor_id')->constrained('colaboradores')->nullOnDelete();

            $table->date('admissao')->nullable()->after('data_nascimento');
            $table->date('desligamento')->nullable()->after('admissao');

            $table->boolean('pcd')->default(false)->after('desligamento');
            $table->boolean('afastado')->default(false)->after('pcd');
            $table->boolean('menor_aprendiz')->default(false)->after('afastado');

            $table->string('raca_cor', 100)->nullable()->after('menor_aprendiz');
            $table->string('nacionalidade', 150)->nullable()->after('raca_cor');
            $table->string('naturalidade', 150)->nullable()->after('nacionalidade');
            $table->string('genero', 100)->nullable()->after('naturalidade');

            $table->boolean('calcula_headcount')->default(true)->after('genero');
            $table->string('estabilidade', 100)->nullable()->after('calcula_headcount');

            $table->string('email', 200)->nullable()->after('estabilidade');
            $table->string('telefone', 30)->nullable()->after('email');
            $table->string('celular', 30)->nullable()->after('telefone');

            $table->string('logradouro', 200)->nullable()->after('celular');
            $table->string('numero_casa', 30)->nullable()->after('logradouro');
            $table->string('complemento', 200)->nullable()->after('numero_casa');
            $table->string('bairro', 150)->nullable()->after('complemento');

            $table->foreignId('cidade_id')->nullable()->after('bairro')->constrained('gestao_cidade')->nullOnDelete();
            $table->foreignId('estado_id')->nullable()->after('cidade_id')->constrained('gestao_estado')->nullOnDelete();
            $table->foreignId('pais_id')->nullable()->after('estado_id')->constrained('gestao_pais')->nullOnDelete();

            $table->string('cep', 20)->nullable()->after('pais_id');

            $table->unique(['matricula'], 'colaboradores_matricula_unique');
        });
    }

    public function down(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropUnique('colaboradores_matricula_unique');

            $table->dropConstrainedForeignId('pais_id');
            $table->dropConstrainedForeignId('estado_id');
            $table->dropConstrainedForeignId('cidade_id');
            $table->dropConstrainedForeignId('superior_imediato_id');
            $table->dropConstrainedForeignId('setor_id');
            $table->dropConstrainedForeignId('filial_id');
            $table->dropConstrainedForeignId('cargo_id');

            $table->dropColumn([
                'matricula',
                'codigo_importacao',
                'nome_social',
                'regime',
                'forma_trabalho',
                'admissao',
                'desligamento',
                'pcd',
                'afastado',
                'menor_aprendiz',
                'raca_cor',
                'nacionalidade',
                'naturalidade',
                'genero',
                'calcula_headcount',
                'estabilidade',
                'email',
                'telefone',
                'celular',
                'logradouro',
                'numero_casa',
                'complemento',
                'bairro',
                'cep',
            ]);
        });
    }
};
