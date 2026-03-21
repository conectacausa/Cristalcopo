<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vinculo_cargo_x_risco', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('risco_id')->constrained('sst_riscos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['cargo_id', 'risco_id'], 'uk_vinculo_cargo_risco');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinculo_cargo_x_risco');
    }
};
