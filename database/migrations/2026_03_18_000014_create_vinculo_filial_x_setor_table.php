<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_filial_x_setor', function (Blueprint $table) {
            $table->id();

            // FK para filial
            $table->foreignId('id_filial')
                  ->constrained('filiais')
                  ->cascadeOnDelete();

            // FK para setor
            $table->foreignId('id_setor')
                  ->constrained('empresa_setores')
                  ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes(); // deleted_at

            // Evita duplicidade (muito importante)
            $table->unique(['id_filial', 'id_setor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_filial_x_setor');
    }
};
