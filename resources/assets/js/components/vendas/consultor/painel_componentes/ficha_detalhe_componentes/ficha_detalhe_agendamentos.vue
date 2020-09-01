<template>
    <div>
        <div class="card-panel" id="card_agendamentos">
            <vue-alert ref="alert"></vue-alert>
            <vue-loading :loading="loading" :is-full-page="true"></vue-loading>

            <md-empty-state
                v-if="ficha == null || ficha_detalhe.agendamentos.length == 0"
                md-icon="date_range"
                md-label="Não há agendamentos criados para esse cliente."
                md-description="Crie um novo agendamento de visita ou de chamada.">
                <a class="btn indigo darken-2 waves-effect waves-light"
                   title="Criar agendamento" @click="novoAgendamento">
                    <i class="material-icons left">add</i>Novo Agendamento
                </a>
            </md-empty-state>

            <section v-else>
                <div class="row">
                    <div class="col s12">
                        <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                           title="Criar agendamento"
                           @click="novoAgendamento"><i class="material-icons left">add</i></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <ul id="collapse_agendamentos"
                            class="collapsible popout">
                            <li v-for="(agendamento, agendamento_i) in listaAgendamentos">
                                <div class="collapsible-header">
                                    <i class="material-icons">date_range</i>
                                    <span>Agendamento de {{agendamento.tipo_agendamento.descricao}}</span>
                                    <span class="new badge right"
                                          :class="{'green darken-3' : agendamento.ativo, 'red' : !agendamento.ativo}"
                                          data-badge-caption="">{{ agendamento.ativo ? 'Ativo' : 'Cancelado' }}</span>
                                </div>

                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <input type="text" id="agendamento_usuario" name="agendamento_usuario"
                                                   disabled
                                                   :value="agendamento.usuario != null && agendamento.usuario != undefined ? agendamento.usuario.funcionario.nome : 'N/a'">
                                            <label for="agendamento_usuario">Usuário que agendou</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <input type="text" id="agendamento_data_hora" name="agendamento_data_hora"
                                                   disabled
                                                   :value="agendamento.data_hora_formatado != null && agendamento.data_hora_formatado != '' ? agendamento.data_hora_formatado : 'N/a'">
                                            <label for="agendamento_data_hora">Data/Hora</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s12"
                                             :class="{'m6' : (agendamento.motivo_cancelamento != null && agendamento.motivo_cancelamento != '')}">
                                            <input type="text" id="agendamento_telefone" name="agendamento_telefone"
                                                   disabled
                                                   :value="agendamento.numero_telefone != null && agendamento.numero_telefone != '' ? agendamento.numero_telefone : 'N/a'">
                                            <label for="agendamento_telefone">Telefone</label>
                                        </div>

                                        <div class="input-field col m6 s12"
                                             v-if="agendamento.motivo_cancelamento != null && agendamento.motivo_cancelamento != ''">
                                            <input type="text" id="agendamento_motivo_cancelamento" name="agendamento_motivo_cancelamento"
                                                   disabled
                                                   :value="agendamento.motivo_cancelamento != null && agendamento.motivo_cancelamento != '' ? agendamento.motivo_cancelamento : 'N/a'">
                                            <label for="agendamento_motivo_cancelamento">Motivo do cancelamento</label>
                                        </div>
                                    </div>

                                    <!--Caso o agendamento seja de visita -->
                                    <section id="secao_endereco_visita" v-if="agendamento.tipo_agendamento.id == 1">
                                        <div class="row">
                                            <div class="input-field col m4 s12">
                                                <input type="text" id="agendamento_cep" name="agendamento_cep"
                                                       disabled
                                                       :value="agendamento.cep != null && agendamento.cep != '' ? agendamento.cep : 'N/a'">
                                                <label for="agendamento_cep">CEP</label>
                                            </div>
                                            <div class="input-field col m8 s12">
                                                <input type="text" id="agendamento_logradouro" name="agendamento_logradouro"
                                                       disabled
                                                       :value="agendamento.logradouro != null && agendamento.logradouro != '' ? agendamento.logradouro : 'N/a'">
                                                <label for="agendamento_logradouro">Endereço(Logradouro)</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col m3 s12">
                                                <input type="text" id="agendamento_numero_endereco" name="agendamento_numero_endereco"
                                                       disabled
                                                       :value="agendamento.numero_endereco != null && agendamento.numero_endereco != '' ? agendamento.numero_endereco : 'N/a'">
                                                <label for="agendamento_numero_endereco">Número</label>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input type="text" id="agendamento_complemento" name="agendamento_complemento"
                                                       disabled
                                                       :value="agendamento.complemento != null && agendamento.complemento != '' ? agendamento.complemento : 'N/a'">
                                                <label for="agendamento_complemento">Complemento</label>
                                            </div>
                                            <div class="input-field col m5 s12">
                                                <input type="text" id="agendamento_bairro" name="agendamento_bairro"
                                                       disabled
                                                       :value="agendamento.bairro != null && agendamento.bairro != '' ? agendamento.bairro : 'N/a'">
                                                <label for="agendamento_bairro">Bairro</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col m6 s12">
                                                <input type="text" id="agendamento_cidade" name="agendamento_cidade"
                                                       disabled
                                                       :value="agendamento.cidade != null && agendamento.cidade != '' ? agendamento.cidade : 'N/a'">
                                                <label for="agendamento_cidade">Cidade</label>
                                            </div>

                                            <div class="input-field col m6 s12">
                                                <input type="text" id="agendamento_uf" name="agendamento_uf"
                                                       disabled
                                                       :value="agendamento.uf != null && agendamento.uf != undefined && agendamento.uf.sigla != '' ? agendamento.uf.sigla : 'N/a'">
                                                <label for="agendamento_uf">Estado (UF)</label>
                                            </div>
                                        </div>
                                    </section>

                                    <div class="row" v-if="agendamento.ativo">
                                        <div class="col s12">
                                            <a class="btn btn-small waves-effect waves-red grey darken-1"
                                               @click="deletaAgendamento(agendamento)">
                                                <i class="material-icons left">delete</i>
                                                Excluir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    name: "ficha_detalhe_agendamentos",
    props:{
        usuarioLogado: {
            type: Object,
            default: null,
        },
        ficha: {
            type: Object,
            default: null,
        }
    },
    data() {
        return {
            ficha_detalhe: this.ficha,
            loading: false,
        }
    },
    watch: {
        ficha(newValue){
            this.ficha_detalhe = newValue
        },
        ficha_detalhe(newValue){
            this.$emit('update:ficha', newValue)
        }
    },
    computed: {
        listaAgendamentos(){
            return _.orderBy(this.ficha_detalhe.agendamentos, ['id'], ['desc'])
        }
    },
    updated() {
        $('#collapse_agendamentos').collapsible();
    },
    methods: {
        novoAgendamento(){
            //this.$refs.ficha_agendamento.cadastraNovoAgendamento(this.ficha_detalhe, this.usuarioLogado)
            let janelaDeAgendamento = this.$root.$refs.painel_atendimento.$refs.ficha_detalhe.$refs.ficha_agendamento
            janelaDeAgendamento.cadastraNovoAgendamento(this.ficha_detalhe, this.usuarioLogado)
        },
        concluiSalvamentoDeDados(agendamentosSalvos){
            this.ficha_detalhe.agendamentos = agendamentosSalvos
        },
        deletaAgendamento(agendamento){
            let motivo = window.prompt('Indique o motivo para cancelar o agendamento.')

            if(motivo == null || motivo == ''){
                Swal.fire('Oops!','É necessário informar um motivo para cancelar o agendamento.','error')
                return
            }

            this.loading = true

            axios.get('/agendamentos/deleta/' + agendamento.id + '/' + motivo)
                .then(response => {
                    this.loading = false
                    Swal.fire('Ótimo!', 'Agendamento cancelado com sucesso.', 'success')
                    agendamento.ativo = false
                })
                .catch(error => {
                    this.loading = false
                    window.scroll(0, 0)
                    Swal.fire('Oops!','Ocorreu um erro ao tentar criar essa agendamento, feche para ver mais detalhes.','error')
                    this.$refs.alert.abreMensagens(error.response)
                })
        }
    }
}
</script>

<style scoped>
    #card_agendamentos {
        min-height: 700px;
    }
</style>
