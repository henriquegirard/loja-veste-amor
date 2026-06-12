@extends('layout.default')

@section('content')
<div id="app_estoque" v-cloak>
    <div class="row">
        <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
            <h2><i class="fa-solid fa-boxes-stacked me-2"></i> Gestão de Estoque</h2>
            <button class="btn btn-primary" @click="abrirModalNovaCategoria">
                <i class="fa-solid fa-plus"></i> Nova Categoria
            </button>
        </div>

        <div class="col-md-12">
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
            
            <div v-else class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Categoria</th>
                                <th class="text-center">Quantidade Atual</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="produtos.length === 0">
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Nenhum produto cadastrado no catálogo.
                                </td>
                            </tr>
                            <tr v-for="produto in produtos" :key="produto.id">
                                <td class="fw-bold align-middle">@{{ produto.categoria }}</td>
                                <td class="text-center align-middle">
                                    <span :class="['badge fs-6', produto.quantidade_atual > 0 ? 'bg-success' : 'bg-danger']">
                                        @{{ produto.quantidade_atual }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-outline-success me-1" title="Registrar Entrada" @click="abrirModal(produto, 'ENTRADA')">
                                        <i class="fa-solid fa-arrow-down"></i> Entrada
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Registrar Saída" @click="abrirModal(produto, 'SAIDA')" :disabled="produto.quantidade_atual <= 0">
                                        <i class="fa-solid fa-arrow-up"></i> Saída
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Movimentação -->
    <div class="modal fade" id="modalMovimentacao" tabindex="-1" aria-hidden="true" ref="modalMovimentacao">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="salvarMovimentacao">
              <div :class="['modal-header text-white', formMovimentacao.tipo === 'ENTRADA' ? 'bg-success' : 'bg-danger']">
                <h5 class="modal-title">
                    <i :class="['fa-solid', formMovimentacao.tipo === 'ENTRADA' ? 'fa-box-open' : 'fa-box']"></i> 
                    Registrar @{{ formMovimentacao.tipo === 'ENTRADA' ? 'Entrada' : 'Saída' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                 <div class="mb-3">
                     <label class="form-label text-muted">Categoria</label>
                     <p class="fs-5 fw-bold mb-0">@{{ produtoSelecionado ? produtoSelecionado.categoria : '' }}</p>
                 </div>
                 <div class="mb-3">
                     <label class="form-label fw-bold">Quantidade</label>
                     <input type="number" class="form-control form-control-lg" v-model.number="formMovimentacao.quantidade" min="1" :max="formMovimentacao.tipo === 'SAIDA' ? (produtoSelecionado ? produtoSelecionado.quantidade_atual : 1) : 9999" required autofocus>
                 </div>
                 <div class="mb-3">
                     <label class="form-label">Realizado por (Nome do Voluntário)</label>
                     <input type="text" class="form-control" v-model="formMovimentacao.realizado_por" required>
                 </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" :disabled="saving">
                    <span v-if="saving" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Salvar
                </button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Nova Categoria -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true" ref="modalCategoria">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <form @submit.prevent="salvarCategoria">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Adicionar Categoria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                 <div class="mb-3">
                     <label class="form-label">Nome da Categoria</label>
                     <input type="text" class="form-control" v-model="novaCategoria.nome" required placeholder="Ex: Roupas de Cama">
                 </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" :disabled="saving">Salvar</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@push('final-scripts')
<script>
    new Vue({
        el: '#app_estoque',
        data() {
            return {
                produtos: [],
                loading: true,
                saving: false,
                produtoSelecionado: null,
                formMovimentacao: {
                    tipo: 'ENTRADA',
                    quantidade: 1,
                    realizado_por: 'Administrador'
                },
                novaCategoria: {
                    nome: ''
                },
                modalMov: null,
                modalCat: null
            }
        },
        mounted() {
            this.modalMov = new bootstrap.Modal(this.$refs.modalMovimentacao);
            this.modalCat = new bootstrap.Modal(this.$refs.modalCategoria);
            this.fetchEstoque();
        },
        methods: {
            fetchEstoque() {
                this.loading = true;
                axios.get('/api/estoque')
                    .then(response => {
                        this.produtos = response.data;
                    })
                    .catch(error => {
                        console.error('Erro ao buscar estoque', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            abrirModalNovaCategoria() {
                this.novaCategoria.nome = '';
                this.modalCat.show();
            },
            salvarCategoria() {
                this.saving = true;
                axios.post('/api/estoque', { categoria: this.novaCategoria.nome })
                    .then(response => {
                        this.produtos.push(response.data);
                        this.modalCat.hide();
                    })
                    .catch(error => {
                        alert('Erro ao criar categoria.');
                        console.error(error);
                    })
                    .finally(() => {
                        this.saving = false;
                    });
            },
            abrirModal(produto, tipo) {
                this.produtoSelecionado = produto;
                this.formMovimentacao.tipo = tipo;
                this.formMovimentacao.quantidade = 1;
                this.modalMov.show();
            },
            salvarMovimentacao() {
                if (!this.produtoSelecionado) return;
                
                this.saving = true;
                axios.post(`/api/estoque/${this.produtoSelecionado.id}/movimentar`, this.formMovimentacao)
                    .then(response => {
                        // Atualiza localmente
                        const index = this.produtos.findIndex(p => p.id === this.produtoSelecionado.id);
                        if (index !== -1) {
                            this.produtos[index].quantidade_atual = response.data.produto.quantidade_atual;
                        }
                        this.modalMov.hide();
                    })
                    .catch(error => {
                        alert(error.response?.data?.message || 'Erro ao movimentar estoque.');
                        console.error(error);
                    })
                    .finally(() => {
                        this.saving = false;
                    });
            }
        }
    });
</script>
@endpush
