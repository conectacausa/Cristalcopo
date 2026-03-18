<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gestao_tela', function (Blueprint $table) {
            $table->integer('ordem')->default(0)->after('icone');
        });
    }

    public function down(): void
    {
        Schema::table('gestao_tela', function (Blueprint $table) {
            $table->dropColumn('ordem');
        });
    }
};
