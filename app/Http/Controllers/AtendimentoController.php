<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipe;
use App\Models\VisitaDoacao;
use App\Models\ProdutoEstoque;
use App\Models\MovimentacaoEstoque;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    public function index()
    {
        return view('atendimentos.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string',
            'nome' => 'required|string',
            'bairro' => 'required|string',
            'itens' => 'array',
        ]);

        try {
            DB::beginTransaction();

            // 1. Encontra ou Cria o Munícipe
            $municipe = Municipe::where('cpf', $request->cpf)->first();
            if (!$municipe) {
                $municipe = new Municipe();
                $municipe->cpf = $request->cpf;
            }
            $municipe->nome = $request->nome;
            $municipe->data_nascimento = $request->data_nascimento;
            $municipe->telefone = $request->telefone;
            $municipe->endereco = $request->endereco;
            $municipe->bairro = $request->bairro;
            $municipe->possui_filhos = $request->possui_filhos ? true : false;
            $municipe->save();

            // 2. Valida Estoque
            $itens = $request->itens ?? [];
            foreach ($itens as $item) {
                $produto = ProdutoEstoque::lockForUpdate()->find($item['produto_estoque_id']);
                if (!$produto || $produto->quantidade_atual < $item['quantidade']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Estoque insuficiente para ' . ($produto ? $produto->categoria : 'item desconhecido')
                    ], 422);
                }
            }

            // 3. Registra a Visita
            $visita = new VisitaDoacao();
            $visita->municipe_id = $municipe->id;
            $visita->outros_materiais = $request->outros_materiais;
            $visita->autorizado_por = $request->autorizado_por;
            $visita->assinatura_recebedor = $request->assinatura_recebedor ? true : false;
            $visita->save();

            // 4. Registra Movimentações e Baixa Estoque
            foreach ($itens as $item) {
                $produto = ProdutoEstoque::find($item['produto_estoque_id']);
                
                // Movimentacao
                $movimentacao = new MovimentacaoEstoque();
                $movimentacao->produto_estoque_id = $produto->id;
                $movimentacao->tipo = 'SAIDA';
                $movimentacao->quantidade = $item['quantidade'];
                $movimentacao->motivo = 'Doação (Atendimento Munícipe ID: ' . $municipe->id . ')';
                $movimentacao->realizado_por = $request->autorizado_por;
                $movimentacao->data_transacao = now();
                $movimentacao->save();

                // Baixa Estoque
                $produto->quantidade_atual -= $item['quantidade'];
                $produto->save();
            }

            DB::commit();

            return response()->json(['message' => 'Atendimento registrado com sucesso', 'municipe' => $municipe]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro interno ao processar o atendimento.', 'error' => $e->getMessage()], 500);
        }
    }
}
