<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_cargo', 150);
            $table->string('codigo_importacao', 100)->nullable()->index();
            $table->unsignedBigInteger('cbo_id')->index();
            $table->unsignedBigInteger('aprovacao_solicitacao_id')->nullable()->index();
            $table->string('status_aprovacao', 50)->default('rascunho')->index();
            $table->boolean('conta_base_jovem_aprendiz')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cbo_id')->references('id')->on('cbos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
