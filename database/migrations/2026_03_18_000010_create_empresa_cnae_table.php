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
        Schema::create('empresa_cnae', function (Blueprint $table) {
            $table->id();

            // Estrutura CNAE
            $table->string('sessao', 5)->nullable();      // Ex: C
            $table->string('divisao', 5)->nullable();     // Ex: 10
            $table->string('grupo', 10)->nullable();      // Ex: 10.1
            $table->string('classe', 10)->nullable();     // Ex: 10.11
            $table->string('subclasse', 15)->nullable();  // Ex: 10.11-2-01

            $table->string('descricao', 255);
            $table->text('nota_explicativa')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('sessao');
            $table->index('divisao');
            $table->index('grupo');
            $table->index('classe');
            $table->index('subclasse');

            // Evita duplicidade de CNAE
            $table->unique('subclasse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_cnae');
    }
};
