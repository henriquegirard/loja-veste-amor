<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    /**
     * Retorna os bairros com mais atendimentos, ordenados por volume.
     * Agregação Geográfica (Inteligência de Dados)
     */
    public function getDemandaPorBairro(int $limit = 10)
    {
        return DB::table('visitas_doacoes')
            ->join('municipes', 'visitas_doacoes.municipe_id', '=', 'municipes.id')
            ->select('municipes.bairro', DB::raw('COUNT(visitas_doacoes.id) as total_atendimentos'))
            ->whereNotNull('municipes.bairro')
            ->where('municipes.bairro', '!=', '')
            ->groupBy('municipes.bairro')
            ->orderByDesc('total_atendimentos')
            ->limit($limit)
            ->get();
    }

    /**
     * Retorna o log de auditoria recente das movimentações de estoque.
     */
    public function getAuditoriaTransacoes(int $limit = 20)
    {
        return DB::table('movimentacoes_estoque')
            ->join('produtos_estoque', 'movimentacoes_estoque.produto_id', '=', 'produtos_estoque.id')
            ->leftJoin('municipes', 'movimentacoes_estoque.municipe_id', '=', 'municipes.id')
            ->select(
                'movimentacoes_estoque.data_transacao',
                'movimentacoes_estoque.tipo',
                'movimentacoes_estoque.quantidade',
                'produtos_estoque.categoria',
                'movimentacoes_estoque.realizado_por',
                'municipes.nome as municipe_nome'
            )
            ->orderByDesc('movimentacoes_estoque.data_transacao')
            ->limit($limit)
            ->get();
    }
}
