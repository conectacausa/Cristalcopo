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
        Schema::create('gestao_permissao', function (Blueprint $table) {
            $table->id();
            $table->string('nome_grupo', 150);
            $table->boolean('situacao')->default(1); // 1 = ativo, 0 = inativo

            $table->timestamps();

            $table->index('situacao');
            $table->index('nome_grupo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_permissao');
    }
};
