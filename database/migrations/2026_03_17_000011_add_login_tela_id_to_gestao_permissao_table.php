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
        Schema::table('gestao_permissao', function (Blueprint $table) {
            $table->foreignId('login_tela_id')
                  ->nullable()
                  ->after('situacao')
                  ->constrained('gestao_tela')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestao_permissao', function (Blueprint $table) {
            $table->dropForeign(['login_tela_id']);
            $table->dropColumn('login_tela_id');
        });
    }
};
