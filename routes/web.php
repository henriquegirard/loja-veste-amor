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
    Route::post('/', function() {
        return redirect()->back();
    })->name('estoque.store');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/', function() {
        return view('dashboard.index');
    })->name('dashboard.index');
});
