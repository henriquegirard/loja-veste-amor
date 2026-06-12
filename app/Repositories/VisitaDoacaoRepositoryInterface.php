<?php

namespace App\Repositories;

interface VisitaDoacaoRepositoryInterface
{
    /**
     * Retorna o relatório mensal consolidado de visitas e doações
     * 
     * @param int $mes
     * @param int $ano
     * @return object
     */
    public function getRelatorioMensal(int $mes, int $ano): object;
}
