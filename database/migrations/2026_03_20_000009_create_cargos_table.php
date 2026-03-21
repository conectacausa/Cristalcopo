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
            $table->string('titulo_cargo', 255);
            $table->string('codigo_importacao', 100)->nullable();
            $table->foreignId('cargo_cbo_id')->constrained('cargos_cbo');
            $table->foreignId('aprovacao_solicitacao_id')->nullable()->constrained('aprovacao_solicitacao')->nullOnDelete();
            $table->string('status_aprovacao', 50)->default('rascunho');
            $table->boolean('conta_base_jovem_aprendiz')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('titulo_cargo');
            $table->index('codigo_importacao');
            $table->index('status_aprovacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
