<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas_doacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('municipe_id');
            $table->timestamp('data_visita')->useCurrent();
            $table->integer('qtd_roupa_cama')->default(0);
            $table->integer('qtd_masculino')->default(0);
            $table->integer('qtd_feminino')->default(0);
            $table->integer('qtd_infantil')->default(0);
            $table->integer('qtd_calcados')->default(0);
            $table->text('outros_materiais')->nullable();
            $table->string('autorizado_por')->nullable();
            
            try {
                $driver = DB::connection()->getDriverName();
            } catch (\Exception $e) {
                $driver = 'pgsql';
            }
            
            if ($driver === 'pgsql') {
                $table->boolean('assinatura_recebedor')->default(false);
            } else {
                $table->tinyInteger('assinatura_recebedor')->default(0);
            }
            
            $table->foreign('municipe_id')->references('id')->on('municipes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas_doacoes');
    }
};
