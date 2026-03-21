<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaborador_importacoes', function (Blueprint $table) {
            $table->id();
            $table->string('arquivo_nome');
            $table->string('arquivo_path')->nullable();
            $table->string('tipo_arquivo', 20)->nullable();
            $table->unsignedInteger('total_linhas')->default(0);
            $table->unsignedInteger('total_processadas')->default(0);
            $table->unsignedInteger('total_inseridas')->default(0);
            $table->unsignedInteger('total_atualizadas')->default(0);
            $table->unsignedInteger('total_erro')->default(0);
            $table->string('status', 30)->default('processando');
            $table->foreignId('colaborador_id')->nullable()->constrained('colaboradores')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaborador_importacoes');
    }
};
