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
        Schema::table('gestao_tela', function (Blueprint $table) {
            $table->foreignId('modulo_id')
                  ->after('id')
                  ->constrained('gestao_modulo')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestao_tela', function (Blueprint $table) {
            $table->dropForeign(['modulo_id']);
            $table->dropColumn('modulo_id');
        });
    }
};
