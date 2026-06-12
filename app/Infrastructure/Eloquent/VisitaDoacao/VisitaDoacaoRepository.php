<?php

namespace App\Infrastructure\Eloquent\VisitaDoacao;

use App\Repositories\VisitaDoacaoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class VisitaDoacaoRepository implements VisitaDoacaoRepositoryInterface
{
    private $eloquent;

    public function __construct(VisitaDoacao $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * Retorna os dados consolidados do mês:
     * 1. Total de pessoas únicas atendidas no período.
     * 2. Somatório total de peças doadas por categoria.
     */
    public function getRelatorioMensal(int $mes, int $ano): object
    {
        // Utilizando o Model injetado (this->eloquent) conforme o padrão do projeto
        $resultado = $this->eloquent->query()
            ->whereMonth('data_visita', $mes)
            ->whereYear('data_visita', $ano)
            ->select([
                DB::raw('COUNT(DISTINCT municipe_id) as total_pessoas_unicas'),
                DB::raw('SUM(qtd_roupa_cama) as total_roupa_cama'),
                DB::raw('SUM(qtd_masculino) as total_masculino'),
                DB::raw('SUM(qtd_feminino) as total_feminino'),
                DB::raw('SUM(qtd_infantil) as total_infantil'),
                DB::raw('SUM(qtd_calcados) as total_calcados'),
            ])
            ->first();
            
        // Se a query retornar vazio/null (ex: sem dados no mês), 
        // garantimos o retorno de zeros para evitar erros no Dashboard.
        if (!$resultado || $resultado->total_pessoas_unicas === null) {
            return (object) [
                'total_pessoas_unicas' => 0,
                'total_roupa_cama' => 0,
                'total_masculino' => 0,
                'total_feminino' => 0,
                'total_infantil' => 0,
                'total_calcados' => 0,
            ];
        }

        return $resultado;
    }
}
