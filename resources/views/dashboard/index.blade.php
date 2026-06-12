@extends('layout.default')

@section('content')
<div id="app_dashboard" v-cloak>
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2><i class="fa-solid fa-chart-pie me-2"></i> Dashboard de Gestão</h2>
        </div>

        <div class="col-md-12" v-if="loading">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        </div>

        <template v-else>
            <!-- Indicadores Dinâmicos Baseados na Auditoria -->
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total de Peças Entregues</h5>
                        <h2 class="display-4 fw-bold">@{{ totalPecasEntregues }}</h2>
                        <small>Nas últimas 100 transações</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total de Peças Recebidas</h5>
                        <h2 class="display-4 fw-bold">@{{ totalPecasRecebidas }}</h2>
                        <small>Nas últimas 100 transações</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-warning text-dark shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Bairro com Maior Demanda</h5>
                        <h2 class="display-5 fw-bold text-truncate" :title="bairroMaisAtendido">@{{ bairroMaisAtendido }}</h2>
                        <small>Base de dados histórica</small>
                    </div>
                </div>
            </div>

            <!-- Georreferenciamento -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="fa-solid fa-map-location-dot me-2"></i> Demanda por Bairro</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            <li v-if="bairros.length === 0" class="list-group-item text-center py-4 text-muted">
                                Nenhum bairro registrado ainda.
                            </li>
                            <li v-for="bairro in bairros" :key="bairro.bairro" class="list-group-item d-flex justify-content-between align-items-center">
                                @{{ bairro.bairro }}
                                <span class="badge bg-primary rounded-pill">@{{ bairro.total }} atendimentos</span>
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
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Tipo</th>
                                    <th>Categoria</th>
                                    <th>Qtd</th>
                                    <th>Realizado Por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="auditoria.length === 0">
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Nenhuma transação registrada.
                                    </td>
                                </tr>
                                <tr v-for="log in auditoria" :key="log.id">
                                    <td class="align-middle">@{{ formatarData(log.data_transacao) }}</td>
                                    <td class="align-middle">
                                        <span :class="['badge', log.tipo === 'ENTRADA' ? 'bg-success' : 'bg-danger']">
                                            @{{ log.tipo }}
                                        </span>
                                    </td>
                                    <td class="align-middle">@{{ log.produto ? log.produto.categoria : 'Produto Removido' }}</td>
                                    <td :class="['align-middle fw-bold', log.tipo === 'ENTRADA' ? 'text-success' : 'text-danger']">
                                        @{{ log.tipo === 'ENTRADA' ? '+' : '-' }}@{{ log.quantidade }}
                                    </td>
                                    <td class="align-middle">@{{ log.realizado_por }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
@stop

@push('final-scripts')
<script>
    new Vue({
        el: '#app_dashboard',
        data() {
            return {
                bairros: [],
                auditoria: [],
                loading: true
            }
        },
        computed: {
            totalPecasEntregues() {
                return this.auditoria
                    .filter(log => log.tipo === 'SAIDA')
                    .reduce((sum, log) => sum + log.quantidade, 0);
            },
            totalPecasRecebidas() {
                return this.auditoria
                    .filter(log => log.tipo === 'ENTRADA')
                    .reduce((sum, log) => sum + log.quantidade, 0);
            },
            bairroMaisAtendido() {
                if (this.bairros.length > 0) {
                    return this.bairros[0].bairro; // Como a API já ordena DESC, o primeiro é o maior
                }
                return '-';
            }
        },
        mounted() {
            this.carregarDados();
        },
        methods: {
            carregarDados() {
                this.loading = true;
                
                Promise.all([
                    axios.get('/api/dashboard/georreferenciamento'),
                    axios.get('/api/dashboard/auditoria')
                ]).then(responses => {
                    this.bairros = responses[0].data;
                    this.auditoria = responses[1].data;
                }).catch(error => {
                    console.error('Erro ao carregar dados do dashboard', error);
                }).finally(() => {
                    this.loading = false;
                });
            },
            formatarData(dataIso) {
                if (!dataIso) return '';
                const data = new Date(dataIso);
                return data.toLocaleString('pt-BR', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
    });
</script>
@endpush
