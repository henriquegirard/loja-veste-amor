@php
use App\Domain\Types\StatusType;
@endphp

@extends('layout.templates.forms.create-panel', ['returnTo' => route('atendimentos.index')])

@section('form-open')
    {!! Html::form()->method('POST')->action(route('atendimentos.store'))->id('form_atendimento')->open() !!}
@stop

@section('panel-description')
    Atendimento: Loja Solidária Veste Amor
@stop

@section('panel-content')
    <div id="app_atendimento" v-cloak>
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
    </div>
@stop

@section('form-close')
    {!! Html::form()->close() !!}
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
                endereco: '',
                bairro: '',
                possui_filhos: false,
                idades_filhos: [],
                
                // Limites Baseados no Estoque Real (mocked initially)
                estoque: {
                    roupa_cama: 15,
                    masculino: 0, // Exemplo de item zerado para testar disable
                    feminino: 40,
                    infantil: 25,
                    calcados: 10
                },

                // Doacoes
                qtd_roupa_cama: 0,
                qtd_masculino: 0,
                qtd_feminino: 0,
                qtd_infantil: 0,
                qtd_calcados: 0,
                outros_materiais: '',
                autorizado_por: '',
                assinatura_recebedor: false,

                // Validacoes
                ultimaVisita: null,
                diasDesdeUltimaVisita: null,
                intervaloMinimo: 30, // dias
                isEditMode: false,
                loading: false
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
        methods: {
            buscarMunicipe() {
                let cleanCpf = this.cpf.replace(/\D/g, '');
                if (cleanCpf.length === 11) {
                    this.loading = true;
                    // Mock da chamada AJAX (deve ser ajustado para ziggy/laroute no projeto real)
                    // $.get(route('api.municipes.findByCpf', {cpf: cleanCpf}))
                    
                    // Exemplo simulado de retorno da API:
                    /*
                    $.get(`/api/municipes/${cleanCpf}`)
                        .done(data => {
                            if (data) {
                                this.municipeId = data.id;
                                this.nome = data.nome;
                                this.data_nascimento = data.data_nascimento;
                                this.telefone = data.telefone;
                                this.endereco = data.endereco;
                                this.bairro = data.bairro;
                                this.possui_filhos = data.possui_filhos;
                                this.isEditMode = true; // Impede a edicao dos dados pessoais
                                
                                if (data.ultima_visita) {
                                    this.ultimaVisita = data.ultima_visita.data_formatada;
                                    this.diasDesdeUltimaVisita = data.ultima_visita.dias;
                                }
                            }
                        })
                        .always(() => {
                            this.loading = false;
                        });
                    */
                } else {
                    this.resetForm();
                }
            },
            resetForm() {
                this.municipeId = null;
                this.nome = '';
                this.data_nascimento = '';
                this.telefone = '';
                this.endereco = '';
                this.bairro = '';
                this.possui_filhos = false;
                this.idades_filhos = [];
                this.ultimaVisita = null;
                this.diasDesdeUltimaVisita = null;
                this.isEditMode = false;
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
            }
        }
    });
</script>
@endpush
