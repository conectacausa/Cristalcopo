<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_responsabilidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('responsabilidade_id')->constrained('cargos_responsabilidades')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['cargo_id', 'responsabilidade_id'], 'uk_cargo_responsabilidade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_responsabilidade');
    }
};
