<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('produto_id');
            $table->enum('tipo', ['ENTRADA', 'SAIDA']);
            $table->integer('quantidade');
            
            // Nulo caso seja entrada de lote genérico
            $table->uuid('municipe_id')->nullable();
            
            // Auditoria obrigatória (Considerando que a tabela users possa existir)
            $table->string('realizado_por'); // Pode ser uuid ou int, usando string genérica para o código inicial
            
            $table->timestamp('data_transacao')->useCurrent();
            
            $table->foreign('produto_id')->references('id')->on('produtos_estoque')->onDelete('cascade');
            $table->foreign('municipe_id')->references('id')->on('municipes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
