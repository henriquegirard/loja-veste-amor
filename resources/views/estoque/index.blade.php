@extends('layout.default')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
        <h2><i class="fa-solid fa-boxes-stacked me-2"></i> Gestão de Estoque</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEntrada">
            <i class="fa-solid fa-plus"></i> Registrar Entrada
        </button>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Categoria</th>
                            <th class="text-center">Quantidade Atual</th>
                            <th class="text-center">Última Atualização</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mocking data since no backend is passed yet in visualization -->
                        <tr>
                            <td class="fw-bold">Roupa de Cama</td>
                            <td class="text-center"><span class="badge bg-success fs-6">15</span></td>
                            <td class="text-center">Hoje às 09:30</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Ver Histórico"><i class="fa-solid fa-clock-rotate-left"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Masculino</td>
                            <td class="text-center"><span class="badge bg-danger fs-6">0</span></td>
                            <td class="text-center">Ontem às 14:00</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Ver Histórico"><i class="fa-solid fa-clock-rotate-left"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Feminino</td>
                            <td class="text-center"><span class="badge bg-success fs-6">40</span></td>
                            <td class="text-center">Hoje às 10:15</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Ver Histórico"><i class="fa-solid fa-clock-rotate-left"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Infantil</td>
                            <td class="text-center"><span class="badge bg-success fs-6">25</span></td>
                            <td class="text-center">10/06/2026</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Ver Histórico"><i class="fa-solid fa-clock-rotate-left"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Calçados</td>
                            <td class="text-center"><span class="badge bg-warning text-dark fs-6">10</span></td>
                            <td class="text-center">10/06/2026</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Ver Histórico"><i class="fa-solid fa-clock-rotate-left"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Entrada -->
<div class="modal fade" id="modalEntrada" tabindex="-1" aria-labelledby="modalEntradaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('estoque.store') ?? '#' }}" method="POST">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="modalEntradaLabel"><i class="fa-solid fa-box-open"></i> Nova Entrada de Estoque</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                 <label class="form-label">Categoria</label>
                 <select class="form-select" name="categoria" required>
                     <option value="">Selecione...</option>
                     <option value="Roupa de Cama">Roupa de Cama</option>
                     <option value="Masculino">Masculino</option>
                     <option value="Feminino">Feminino</option>
                     <option value="Infantil">Infantil</option>
                     <option value="Calçados">Calçados</option>
                     <option value="Outros">Outros Materiais</option>
                 </select>
             </div>
             <div class="mb-3">
                 <label class="form-label">Quantidade Recebida</label>
                 <input type="number" class="form-control" name="quantidade" min="1" required>
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Salvar Entrada</button>
          </div>
      </form>
    </div>
  </div>
</div>
@stop
