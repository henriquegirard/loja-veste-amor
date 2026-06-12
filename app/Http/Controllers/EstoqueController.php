<?php

namespace App\Http\Controllers;

use App\Models\ProdutoEstoque;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    /**
     * Retorna o catálogo de produtos e seus saldos atuais.
     */
    public function index()
    {
        $produtos = ProdutoEstoque::orderBy('categoria')->get();
        return response()->json($produtos);
    }

    /**
     * Registra uma movimentação de estoque (Entrada ou Saída).
     */
    public function movimentar(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:ENTRADA,SAIDA',
            'quantidade' => 'required|integer|min:1',
            'municipe_id' => 'nullable|uuid',
            'realizado_por' => 'required|string|max:255',
        ]);

        $produto = ProdutoEstoque::findOrFail($id);

        if ($request->tipo === 'SAIDA' && $produto->quantidade_atual < $request->quantidade) {
            return response()->json([
                'message' => 'Quantidade insuficiente no estoque para esta saída.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Cria o log de auditoria
            $movimentacao = MovimentacaoEstoque::create([
                'produto_id' => $produto->id,
                'tipo' => $request->tipo,
                'quantidade' => $request->quantidade,
                'municipe_id' => $request->municipe_id,
                'realizado_por' => $request->realizado_por,
            ]);

            // Atualiza o saldo do produto
            if ($request->tipo === 'ENTRADA') {
                $produto->quantidade_atual += $request->quantidade;
            } else {
                $produto->quantidade_atual -= $request->quantidade;
            }
            $produto->save();

            DB::commit();

            return response()->json([
                'message' => 'Movimentação registrada com sucesso!',
                'produto' => $produto,
                'movimentacao' => $movimentacao
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao registrar movimentação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo produto no catálogo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:255|unique:produtos_estoque,categoria',
            'quantidade_inicial' => 'nullable|integer|min:0'
        ]);

        $produto = ProdutoEstoque::create([
            'categoria' => $request->categoria,
            'quantidade_atual' => $request->quantidade_inicial ?? 0
        ]);

        return response()->json($produto, 201);
    }
}
