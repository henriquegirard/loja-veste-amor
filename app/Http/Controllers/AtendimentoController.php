<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function index()
    {
        return view('atendimentos.index');
    }

    public function store(Request $request)
    {
        // Apenas um mock para o form submeter e não dar erro na visualização
        return redirect()->back();
    }
}
