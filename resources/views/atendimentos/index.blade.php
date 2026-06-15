@php
use App\Domain\Types\StatusType;
@endphp

@extends('layout.templates.forms.create-panel', ['returnTo' => route('atendimentos.index')])

@section('form-open')
    <!-- form handled by Vue internally -->
@stop

@section('panel-description')
    Atendimento: Loja Solidária Veste Amor
@stop

@section('panel-content')
    <div id="app_atendimento" v-cloak>
        <form @submit.prevent="salvarAtendimento" id="form_atendimento">
            <!-- Busca de CPF -->
            <div class="row mb-4">
                <div class="col-md-6 offset-md-3">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" v-model="cpf" placeholder="Digite o CPF para buscar ou cadastrar..." maxlength="14" autofocus>
                    </div>
                    <div v-if="mensagemIntervalo" :class="['alert mt-2', isIntervaloValido ? 'alert-warning' : 'alert-danger']" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i> @{{ mensagemIntervalo }}
                    </div>
                </div>
            </div>

            <hr>

            <!-- Formulário (Exibido apenas se um CPF válido for digitado ou se estiver cadastrando um novo) -->
            <div v-show="cpf.length >= 11">
                @include('atendimentos.form')
            </div>
        </form>
    </div>
@stop

@section('form-close')
@stop

@push('final-scripts')
<script>
    new Vue({
        el: '#app_atendimento',
        data() {
            return {
                cpf: '',
                municipeId: null,
                nome: '',
                data_nascimento: '',
                telefone: '',
                cep: '',
                endereco: '',
                bairro: '',
                possui_filhos: false,
                idades_filhos: [],
                
                // Estoque carregado da API
                produtosEstoque: [],

                // Doacoes e Finalizacao
                outros_materiais: '',
                autorizado_por: '{{ Auth::check() ? Auth::user()->name : "Administrador" }}',
                assinatura_recebedor: false,

                // Validacoes
                ultimaVisita: null,
                diasDesdeUltimaVisita: null,
                intervaloMinimo: 30, // dias
                isEditMode: false,
                loading: false,
                saving: false
            }
        },
        computed: {
            isIntervaloValido() {
                if (this.diasDesdeUltimaVisita === null) return true;
                return this.diasDesdeUltimaVisita >= this.intervaloMinimo;
            },
            mensagemIntervalo() {
                if (!this.ultimaVisita) return null;
                if (!this.isIntervaloValido) {
                    return `Atenção: A última visita ocorreu há ${this.diasDesdeUltimaVisita} dias (em ${this.ultimaVisita}). O intervalo mínimo permitido para nova retirada é de ${this.intervaloMinimo} dias.`;
                }
                return `Última visita registrada há ${this.diasDesdeUltimaVisita} dias (em ${this.ultimaVisita}). Está dentro do período permitido.`;
            }
        },
        mounted() {
            this.carregarEstoque();
        },
        methods: {
            carregarEstoque() {
                axios.get('/api/estoque')
                    .then(response => {
                        this.produtosEstoque = response.data.map(p => ({
                            ...p,
                            qtd_saida: 0
                        }));
                    })
                    .catch(error => {
                        console.error("Erro ao buscar estoque:", error);
                    });
            },
            buscarMunicipe() {
                let cleanCpf = this.cpf.replace(/\D/g, '');
                if (cleanCpf.length === 11) {
                    this.loading = true;
                    
                    axios.get(`/api/municipes/${cleanCpf}`)
                        .then(response => {
                            if (response.data) {
                                let data = response.data;
                                this.municipeId = data.id;
                                this.nome = data.nome;
                                this.data_nascimento = data.data_nascimento;
                                this.telefone = data.telefone;
                                // Assume que se tem endereço/bairro e não tem CEP salvo, o CEP fica vazio mas o endereço preenche.
                                // Idealmente CEP seria salvo no BD também, mas preenchemos os outros.
                                this.endereco = data.endereco;
                                this.bairro = data.bairro;
                                this.possui_filhos = data.possui_filhos;
                                this.isEditMode = true; 
                                
                                if (data.ultima_visita) {
                                    this.ultimaVisita = data.ultima_visita.data_formatada;
                                    this.diasDesdeUltimaVisita = data.ultima_visita.dias;
                                } else {
                                    this.ultimaVisita = null;
                                    this.diasDesdeUltimaVisita = null;
                                }
                            } else {
                                this.resetForm();
                            }
                        })
                        .catch(error => {
                            if(error.response && error.response.status === 404) {
                                this.resetForm(); // Nao encontrou, cadastra novo
                            } else {
                                console.error('Erro ao buscar munícipe', error);
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                } else {
                    this.resetForm();
                }
            },
            buscarCep() {
                let cleanCep = this.cep.replace(/\D/g, '');
                if (cleanCep.length === 8) {
                    axios.get(`https://viacep.com.br/ws/${cleanCep}/json/`)
                        .then(response => {
                            if (!response.data.erro) {
                                this.endereco = response.data.logradouro + (response.data.complemento ? ' - ' + response.data.complemento : '');
                                this.bairro = response.data.bairro;
                            } else {
                                Swal.fire('Oops...', 'CEP não encontrado.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error("Erro ao buscar CEP:", error);
                        });
                }
            },
            resetForm() {
                this.municipeId = null;
                this.nome = '';
                this.data_nascimento = '';
                this.telefone = '';
                this.cep = '';
                this.endereco = '';
                this.bairro = '';
                this.possui_filhos = false;
                this.idades_filhos = [];
                this.ultimaVisita = null;
                this.diasDesdeUltimaVisita = null;
                this.isEditMode = false;
            },
            salvarAtendimento(event) {
                if (!this.isIntervaloValido) {
                    Swal.fire('Atenção', 'O munícipe ainda não atingiu o intervalo mínimo para retirar novas doações.', 'warning');
                    return;
                }

                if (!this.nome || !this.data_nascimento || !this.cep || !this.bairro) {
                    Swal.fire('Atenção', 'Por favor, preencha todos os campos obrigatórios do Munícipe (marcados com *).', 'warning');
                    return;
                }

                // Prepara os itens que sofrerão saída
                let itensSaida = this.produtosEstoque.filter(p => p.qtd_saida > 0).map(p => ({
                    produto_estoque_id: p.id,
                    quantidade: p.qtd_saida
                }));

                if (itensSaida.length === 0 && this.outros_materiais.trim() === '') {
                    Swal.fire('Atenção', "Por favor, informe a quantidade de pelo menos um item doado ou descreva 'Outros Materiais'.", 'warning');
                    return;
                }

                this.saving = true;

                let payload = {
                    cpf: this.cpf.replace(/\D/g, ''),
                    nome: this.nome,
                    data_nascimento: this.data_nascimento,
                    telefone: this.telefone,
                    endereco: this.endereco,
                    bairro: this.bairro,
                    possui_filhos: this.possui_filhos,
                    itens: itensSaida,
                    outros_materiais: this.outros_materiais,
                    autorizado_por: this.autorizado_por,
                    assinatura_recebedor: this.assinatura_recebedor
                };

                axios.post('/api/atendimentos', payload)
                    .then(response => {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Atendimento registrado com sucesso!',
                            icon: 'success',
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            window.location.reload(); // Recarrega a página para limpar e atualizar o estoque
                        });
                    })
                    .catch(error => {
                        console.error("Erro ao salvar atendimento:", error);
                        Swal.fire({
                            title: 'Erro!',
                            text: error.response?.data?.message || "Ocorreu um erro ao registrar o atendimento. Verifique os dados e tente novamente.",
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    })
                    .finally(() => {
                        this.saving = false;
                    });
            }
        },
        watch: {
            cpf() {
                let cleanCpf = this.cpf.replace(/\D/g, '');
                if (cleanCpf.length === 11) {
                    this.buscarMunicipe();
                } else if (cleanCpf.length < 11 && this.isEditMode) {
                    this.resetForm();
                }
            },
            cep() {
                let cleanCep = this.cep.replace(/\D/g, '');
                if (cleanCep.length === 8 && !this.isEditMode) {
                    this.buscarCep();
                }
            }
        }
    });
</script>
@endpush
