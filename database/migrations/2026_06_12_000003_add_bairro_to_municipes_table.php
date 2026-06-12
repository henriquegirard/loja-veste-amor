<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('municipes', function (Blueprint $table) {
            if (!Schema::hasColumn('municipes', 'bairro')) {
                // Adiciona a coluna bairro indexada para a inteligência de dados geográfica
                $table->string('bairro')->nullable()->after('endereco');
                $table->index('bairro');
            }
        });
    }

    public function down(): void
    {
        Schema::table('municipes', function (Blueprint $table) {
            if (Schema::hasColumn('municipes', 'bairro')) {
                $table->dropIndex(['bairro']);
                $table->dropColumn('bairro');
            }
        });
    }
};
