<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_filial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('filial_id')->constrained('empresa_filial')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['cargo_id', 'filial_id'], 'uk_vinculo_cargo_filial');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_filial');
    }
};
