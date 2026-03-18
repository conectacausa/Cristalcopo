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
        Schema::create('gestao_modulo', function (Blueprint $table) {
            $table->id();
            $table->string('nome_modulo', 150);
            $table->unsignedInteger('ordem')->default(0);

            $table->timestamps();

            $table->index('ordem');
            $table->unique('nome_modulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_modulo');
    }
};
