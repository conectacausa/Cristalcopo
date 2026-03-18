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
        Schema::create('empresa_filial', function (Blueprint $table) {
            $table->id();

            $table->string('razao_social', 200);
            $table->string('cnpj', 14)->unique();
            $table->string('nome_fantasia', 200);
            $table->date('data_abertura');

            $table->foreignId('porte_id')
                ->constrained('empresa_porte')
                ->restrictOnDelete();

            $table->foreignId('natureza_juridica_id')
                ->constrained('empresa_nat_juridica')
                ->restrictOnDelete();

            $table->enum('tipo', ['matriz', 'filial']);
            $table->boolean('situacao')->default(true);

            $table->string('telefone1', 20)->nullable();
            $table->string('telefone2', 20)->nullable();
            $table->string('email', 150)->nullable();

            $table->string('logradouro', 200)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('bairro', 150)->nullable();

            $table->foreignId('cidade_id')
                ->constrained('gestao_cidade')
                ->restrictOnDelete();

            $table->foreignId('estado_id')
                ->constrained('gestao_estado')
                ->restrictOnDelete();

            $table->foreignId('pais_id')
                ->constrained('gestao_pais')
                ->restrictOnDelete();

            $table->string('complemento', 200)->nullable();
            $table->string('cep', 8)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('razao_social');
            $table->index('nome_fantasia');
            $table->index('situacao');
            $table->index('tipo');
            $table->index('cidade_id');
            $table->index('estado_id');
            $table->index('pais_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_filial');
    }
};
