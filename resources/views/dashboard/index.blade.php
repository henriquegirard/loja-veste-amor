@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2><i class="fa-solid fa-chart-pie me-2"></i> Dashboard de Gestão</h2>
    </div>

    <!-- Indicadores -->
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Total de Atendimentos (Mês)</h5>
                <h2 class="display-4 fw-bold">142</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Peças Doadas (Mês)</h5>
                <h2 class="display-4 fw-bold">658</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-warning text-dark shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="card-title">Estoque Crítico</h5>
                <h2 class="display-4 fw-bold">1 <span class="fs-5 fw-normal">Categoria</span></h2>
                <small>Masculino (0 itens)</small>
            </div>
        </div>
    </div>

    <!-- Georreferenciamento -->
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-map-location-dot me-2"></i> Demanda por Bairro</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Centro
                        <span class="badge bg-primary rounded-pill">45 atendimentos</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Vila Nova
                        <span class="badge bg-primary rounded-pill">32 atendimentos</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Jardim América
                        <span class="badge bg-primary rounded-pill">28 atendimentos</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Bela Vista
                        <span class="badge bg-primary rounded-pill">15 atendimentos</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Santa Cruz
                        <span class="badge bg-primary rounded-pill">9 atendimentos</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Auditoria -->
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-list-check me-2"></i> Log de Auditoria (Recentes)</h5>
                <button class="btn btn-sm btn-outline-secondary">Ver Relatório Completo</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Data/Hora</th>
                            <th>Tipo</th>
                            <th>Categoria</th>
                            <th>Qtd</th>
                            <th>Realizado Por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12/06 09:15</td>
                            <td><span class="badge bg-danger">SAÍDA</span></td>
                            <td>Feminino</td>
                            <td class="text-danger fw-bold">-3</td>
                            <td>Operador 1</td>
                        </tr>
                        <tr>
                            <td>12/06 09:10</td>
                            <td><span class="badge bg-danger">SAÍDA</span></td>
                            <td>Infantil</td>
                            <td class="text-danger fw-bold">-5</td>
                            <td>Operador 1</td>
                        </tr>
                        <tr>
                            <td>12/06 08:30</td>
                            <td><span class="badge bg-success">ENTRADA</span></td>
                            <td>Roupa de Cama</td>
                            <td class="text-success fw-bold">+15</td>
                            <td>Gestor Silva</td>
                        </tr>
                        <tr>
                            <td>11/06 17:45</td>
                            <td><span class="badge bg-danger">SAÍDA</span></td>
                            <td>Masculino</td>
                            <td class="text-danger fw-bold">-2</td>
                            <td>Operador 2</td>
                        </tr>
                        <tr>
                            <td>11/06 14:00</td>
                            <td><span class="badge bg-success">ENTRADA</span></td>
                            <td>Feminino</td>
                            <td class="text-success fw-bold">+50</td>
                            <td>Gestor Silva</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
