<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtendimentoController;

Auth::routes(['register' => false]); // Desabilita o registro público para sistema interno

Route::get('/', function () {
    return redirect()->route('atendimentos.index');
});

Route::get('/setup-admin', function () {
    if (!\App\Models\User::where('email', 'admin@vesteamor.com')->exists()) {
        $u = new \App\Models\User();
        $u->name = 'Administrador';
        $u->email = 'admin@vesteamor.com';
        $u->password = bcrypt('vesteamor123');
        $u->save();
        return 'Usuário admin criado com sucesso: admin@vesteamor.com / vesteamor123';
    }
    return 'O usuário admin já existe.';
});

Route::middleware('auth')->group(function () {
    Route::prefix('atendimentos')->group(function () {
        Route::get('/', [AtendimentoController::class, 'index'])->name('atendimentos.index');
        Route::post('/', [AtendimentoController::class, 'store'])->name('atendimentos.store');
    });

    Route::prefix('estoque')->group(function () {
        Route::get('/', function() {
            return view('estoque.index');
        })->name('estoque.index');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', function() {
            return view('dashboard.index');
        })->name('dashboard.index');
    });

    // API Routes para Vue.js
    Route::prefix('api')->group(function () {
        // Estoque
        Route::get('/estoque', [\App\Http\Controllers\EstoqueController::class, 'index']);
        Route::post('/estoque', [\App\Http\Controllers\EstoqueController::class, 'store']);
        Route::post('/estoque/{id}/movimentar', [\App\Http\Controllers\EstoqueController::class, 'movimentar']);

        // Munícipes
        Route::get('/municipes/{cpf}', [\App\Http\Controllers\MunicipeController::class, 'findByCpf']);

        // Dashboard
        Route::get('/dashboard/georreferenciamento', [\App\Http\Controllers\DashboardController::class, 'georreferenciamento']);
        Route::get('/dashboard/auditoria', [\App\Http\Controllers\DashboardController::class, 'auditoria']);

        // Atendimento API endpoint
        Route::post('/atendimentos', [\App\Http\Controllers\AtendimentoController::class, 'store']);
    });
});
