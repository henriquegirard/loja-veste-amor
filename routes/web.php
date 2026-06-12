<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtendimentoController;

Route::get('/', function () {
    return redirect()->route('atendimentos.index');
});

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

    // Dashboard
    Route::get('/dashboard/georreferenciamento', [\App\Http\Controllers\DashboardController::class, 'georreferenciamento']);
    Route::get('/dashboard/auditoria', [\App\Http\Controllers\DashboardController::class, 'auditoria']);
});
