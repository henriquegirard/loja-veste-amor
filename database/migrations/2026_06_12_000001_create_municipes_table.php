<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->string('cpf')->unique(); 
            $table->string('telefone')->nullable();
            $table->text('endereco')->nullable();
            
            // Adaptative Pattern based on Technical Report (PostgreSQL default, Oracle fallback)
            try {
                $driver = DB::connection()->getDriverName();
            } catch (\Exception $e) {
                $driver = 'pgsql'; // Default to pgsql if connection fails during generation
            }
            
            if ($driver === 'pgsql') {
                $table->boolean('possui_filhos')->default(false);
                $table->jsonb('idades_filhos')->nullable();
            } else {
                $table->tinyInteger('possui_filhos')->default(0);
                $table->text('idades_filhos')->nullable();
            }
            
            $table->timestamp('criado_em')->useCurrent();
            
            $table->index('cpf');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipes');
    }
};
