<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_competencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('competencia_id')->constrained('cargos_competencias')->cascadeOnDelete();
            $table->unsignedTinyInteger('nota')->nullable();
            $table->timestamps();

            $table->unique(['cargo_id', 'competencia_id'], 'uk_cargo_competencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_competencia');
    }
};
