<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sst_riscos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 150);
            $table->string('grupo_risco', 50)->nullable();
            $table->boolean('ativo')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('descricao');
            $table->index('grupo_risco');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sst_riscos');
    }
};
