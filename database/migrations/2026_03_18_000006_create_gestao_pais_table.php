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
        Schema::create('gestao_pais', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('iso2', 2)->unique();
            $table->string('iso3', 3)->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestao_pais');
    }
};
