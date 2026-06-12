<?php

namespace App\Services;

use App\Infrastructure\Eloquent\Estoque\ProdutoEstoque;
use App\Infrastructure\Eloquent\Estoque\MovimentacaoEstoque;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class EstoqueService
{
    /**
     * Registra a ENTRADA de estoque para uma categoria.
     */
    public function registrarEntrada(string $categoria, int $quantidade, string $usuarioId): ProdutoEstoque
    {
        try {
            DB::beginTransaction();

            $produto = ProdutoEstoque::firstOrCreate(
                ['categoria' => $categoria],
                ['id' => Str::uuid(), 'quantidade_atual' => 0]
            );

            $produto->quantidade_atual += $quantidade;
            $produto->save();

            MovimentacaoEstoque::create([
                'id' => Str::uuid(),
                'produto_id' => $produto->id,
                'tipo' => 'ENTRADA',
                'quantidade' => $quantidade,
                'realizado_por' => $usuarioId,
            ]);

            DB::commit();
            return $produto;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Registra a SAÍDA de estoque atrelada a um munícipe.
     */
    public function registrarSaida(string $categoria, int $quantidade, string $municipeId, string $usuarioId): ProdutoEstoque
    {
        try {
            DB::beginTransaction();

            // lockForUpdate previne race conditions durante o registro simultâneo
            $produto = ProdutoEstoque::where('categoria', $categoria)->lockForUpdate()->first();

            if (!$produto) {
                throw new Exception("Produto da categoria '{$categoria}' não encontrado no estoque.");
            }

            if ($produto->quantidade_atual < $quantidade) {
                throw new Exception("Estoque insuficiente para '{$categoria}'. Saldo: {$produto->quantidade_atual}.");
            }

            $produto->quantidade_atual -= $quantidade;
            $produto->save();

            MovimentacaoEstoque::create([
                'id' => Str::uuid(),
                'produto_id' => $produto->id,
                'tipo' => 'SAIDA',
                'quantidade' => $quantidade,
                'municipe_id' => $municipeId,
                'realizado_por' => $usuarioId,
            ]);

            DB::commit();
            return $produto;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
