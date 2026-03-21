<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cargos_cursos')->cascadeOnDelete();
            $table->string('tipo', 20);
            $table->timestamps();

            $table->unique(['cargo_id', 'curso_id'], 'uk_cargo_curso');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_curso');
    }
};
