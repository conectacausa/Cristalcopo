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
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();

            $table->string('nome_completo', 200);
            $table->string('cpf', 11)->unique(); // sem máscara
            $table->date('data_nascimento');

            $table->string('senha');

            $table->boolean('situacao')->default(1); // 1 = ativo, 0 = inativo

            $table->foreignId('permissao_id')
                  ->default(1)
                  ->constrained('gestao_permissao')
                  ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes(); // deleted_at

            $table->index('cpf');
            $table->index('situacao');
            $table->index('permissao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};
