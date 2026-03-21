<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestao_configuracao', function (Blueprint $table) {
            $table->id();
            $table->boolean('cargo_tem_aprovacao')->default(false);
            $table->unsignedBigInteger('cargo_fluxo_aprovacao_id')->nullable();
            $table->timestamps();
        });

        DB::table('gestao_configuracao')->insert([
            'cargo_tem_aprovacao' => false,
            'cargo_fluxo_aprovacao_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('gestao_configuracao');
    }
};
