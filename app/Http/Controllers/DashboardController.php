<?php

namespace App\Http\Controllers;

use App\Models\Municipe;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Retorna os dados de georreferenciamento (Bairros x Quantidade de Municipes).
     */
    public function georreferenciamento()
    {
        $dados = Municipe::select('bairro', DB::raw('count(*) as total'))
            ->whereNotNull('bairro')
            ->where('bairro', '!=', '')
            ->groupBy('bairro')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($dados);
    }

    /**
     * Retorna o histórico de auditoria de movimentações de estoque.
     */
    public function auditoria()
    {
        $auditoria = MovimentacaoEstoque::with(['produto', 'municipe'])
            ->orderBy('data_transacao', 'desc')
            ->limit(100) // Traz as últimas 100 transações por padrão
            ->get();

        return response()->json($auditoria);
    }
}
