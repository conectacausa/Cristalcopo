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
        Schema::create('vinculo_filial_x_setor', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('filial_id');
            $table->unsignedBigInteger('setor_id');

            $table->timestamps();

            $table->unique(['filial_id', 'setor_id'], 'uq_vinculo_filial_setor');

            $table->foreign('filial_id', 'fk_vinculo_filial_setor_filial')
                ->references('id')
                ->on('empresa_filial')
                ->onDelete('cascade');

            $table->foreign('setor_id', 'fk_vinculo_filial_setor_setor')
                ->references('id')
                ->on('empresa_setores')
                ->onDelete('cascade');

            $table->index('filial_id', 'idx_vinculo_filial_setor_filial');
            $table->index('setor_id', 'idx_vinculo_filial_setor_setor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vinculo_filial_x_setor', function (Blueprint $table) {
            $table->dropForeign('fk_vinculo_filial_setor_filial');
            $table->dropForeign('fk_vinculo_filial_setor_setor');
            $table->dropUnique('uq_vinculo_filial_setor');
            $table->dropIndex('idx_vinculo_filial_setor_filial');
            $table->dropIndex('idx_vinculo_filial_setor_setor');
        });

        Schema::dropIfExists('vinculo_filial_x_setor');
    }
};
