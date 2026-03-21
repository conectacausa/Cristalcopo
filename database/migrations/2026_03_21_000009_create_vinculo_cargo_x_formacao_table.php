<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_formacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('formacao_id')->constrained('cargos_formacoes')->cascadeOnDelete();
            $table->string('tipo', 20);
            $table->timestamps();

            $table->unique(['cargo_id', 'formacao_id'], 'uk_cargo_formacao');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_formacao');
    }
};
