<template>
    <div>
        <div id="modalFichaDetalhe" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>{{fichaDetalhe.cliente.nome}}</h4>

                <div class="row">
                    <div class="col s12">
                        <ul class="tabs" id="tabs_ficha_detalhes">
                            <li class="tab col s3"><a href="#abaDadosPessoais" class="active">Dados Pessoais</a></li>
                            <li class="tab col s3"><a href="#abaEnderecos">Endereços</a></li>
                            <li class="tab col s3"><a href="#abaEmprestimos">Empréstimos</a></li>
                            <li class="tab col s3"><a href="#abaAgendamentos">Agendamentos</a></li>
                        </ul>
                    </div>
                    <div id="abaDadosPessoais" class="col s12">
                        <vue-ficha-aba-pessoais :ficha="fichaDetalhe"></vue-ficha-aba-pessoais>
                    </div>
                    <div id="abaEnderecos" class="col s12">
                        <vue-ficha-aba-enderecos :ficha="fichaDetalhe"></vue-ficha-aba-enderecos>
                    </div>
                    <div id="abaEmprestimos" class="col s12">
                        <vue-ficha-aba-emprestimos :ficha="fichaDetalhe"></vue-ficha-aba-emprestimos>
                    </div>
                    <div id="abaAgendamentos" class="col s12">
                        <vue-ficha-aba-agendamentos :ficha.sync="fichaDetalhe"
                                                    :usuario-logado="usuarioLogado"></vue-ficha-aba-agendamentos>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-small red darken-1 waves-effect waves-light" @click="fechaFichaDetalhe">
                    <i class="material-icons left">cancel</i>
                    FECHAR
                </a>

                <vue-ficha-ignora-cliente :ficha-id="fichaDetalhe.id" @clienteIgnorado="fechaFichaDetalhe"></vue-ficha-ignora-cliente>
            </div>
        </div>

        <vue-ficha-agendamento ref="ficha_agendamento" @emissaoDeDadosSalvos="atualizaAgendamentos"></vue-ficha-agendamento>
    </div>
</template>

<script>
export default {
    name: "ficha_detalhe",
    data(){
        return {
            usuarioLogado: null,

            fichaDetalhe: {
                id: null,
                agendamentos: [],
                carteira_id: null,
                cliente: {
                    id:null,
                    atualizado_confirmeonline: 0,
                    cargos: [],
                    dados_bancarios: [],
                    emprestimos: [],
                    enderecos: [],
                    cpf: null,
                    data_nascimento: null,
                    data_nascimento_br: null,
                    dt_ult_atu_confirme:null,
                    dt_ult_atu_portal:null,
                    idade:null,
                    mae:null,
                    mae_cpf:null,
                    matricula:null,
                    nome:null,
                    pai:null,
                    rg:null,
                    telefones_para_exibir: [],
                    emails_para_exibir: [],
                },
                clientes_id: null,
                historico_status: [],
                quantidade_de_agendamentos_de_chamada: 0,
                quantidade_de_agendamentos_de_visita: 0,
                status_atual: {
                    id:null,
                    descricao: null,
                    motivo_ficha_ignorada:null,
                },
                ultimo_acesso: null,
            },
            abreJanelaForm: false,
            janelaForm: null,
            tabsJanela: null,
        }
    },
    watch: {
        abreJanelaForm(newValue) {
            if(newValue) {
                this.janelaForm.open()
            }else{
                this.janelaForm.close()
                this.janelaForm.destroy()
            }
        }
    },
    mounted() {
        this.instanciaJanela()
        this.instanciaTabs()
    },
    methods: {
        instanciaJanela(){
            const elemento = document.getElementById('modalFichaDetalhe')
            //const instancia = M.Modal.init(elemento, {dismissible: false})
            const instancia = M.Modal.init(elemento, {
                dismissible: false,
                onCloseEnd: () => {
                    this.eventoDeFechamentoDeJanela()
                },
                onOpenEnd: () => {
                    $(elemento).css('transform', '')
                },
            })
            this.janelaForm = instancia
        },
        instanciaTabs(){
            let elemento = document.getElementById('tabs_ficha_detalhes')
            let instancia = M.Tabs.init(elemento);
            this.tabsJanela = instancia
        },
        limpaCamposDaFicha(){
            this.fichaDetalhe.id =  null
            this.fichaDetalhe.quantidade_de_agendamentos_de_chamada =  0
            this.fichaDetalhe.quantidade_de_agendamentos_de_visita =  0
            this.fichaDetalhe.ultimo_acesso =  null
            this.fichaDetalhe.agendamentos =  []
            this.fichaDetalhe.historico_status = []
            this.fichaDetalhe.cliente.enderecos = []
            this.fichaDetalhe.carteira_id =  null
            this.fichaDetalhe.cliente.id = null
            this.fichaDetalhe.cliente.atualizado_confirmeonline =  0
            this.fichaDetalhe.cliente.cargos =  []
            this.fichaDetalhe.cliente.dados_bancarios =  []
            this.fichaDetalhe.cliente.emprestimos =  []
            this.fichaDetalhe.cliente.cpf =  null
            this.fichaDetalhe.cliente.data_nascimento =  null
            this.fichaDetalhe.cliente.data_nascimento_br =  null
            this.fichaDetalhe.cliente.dt_ult_atu_confirme = null
            this.fichaDetalhe.cliente.dt_ult_atu_portal = null
            this.fichaDetalhe.cliente.idade = null
            this.fichaDetalhe.cliente.mae = null
            this.fichaDetalhe.cliente.mae_cpf = null
            this.fichaDetalhe.cliente.matricula = null
            this.fichaDetalhe.cliente.nome = null
            this.fichaDetalhe.cliente.pai = null
            this.fichaDetalhe.cliente.rg = null
            this.fichaDetalhe.cliente.telefones_para_exibir =  []
            this.fichaDetalhe.cliente.emails_para_exibir =  []
            this.fichaDetalhe.clientes_id =  null
            this.fichaDetalhe.status_atual.id = null
            this.fichaDetalhe.status_atual.descricao =  null
            this.fichaDetalhe.status_atual.motivo_ficha_ignorada = null
        },
        preencheDadosFicha(ficha){
            this.fichaDetalhe.id = ficha.id
            this.fichaDetalhe.quantidade_de_agendamentos_de_chamada =  ficha.quantidade_de_agendamentos_de_chamada
            this.fichaDetalhe.quantidade_de_agendamentos_de_visita =  ficha.quantidade_de_agendamentos_de_visita
            this.fichaDetalhe.ultimo_acesso =  ficha.ultimo_acesso
            this.fichaDetalhe.agendamentos =  ficha.agendamentos
            this.fichaDetalhe.historico_status = ficha.historico_status
            this.fichaDetalhe.cliente.enderecos = ficha.cliente.enderecos
            this.fichaDetalhe.carteira_id =  ficha.carteira_id
            this.fichaDetalhe.cliente.id = ficha.cliente.id
            this.fichaDetalhe.cliente.atualizado_confirmeonline =  ficha.cliente.atualizado_confirmeonline
            this.fichaDetalhe.cliente.cargos =  ficha.cliente.cargos
            this.fichaDetalhe.cliente.dados_bancarios =  ficha.cliente.dados_bancarios
            this.fichaDetalhe.cliente.emprestimos =  ficha.cliente.emprestimos
            this.fichaDetalhe.cliente.cpf =  ficha.cliente.cpf
            this.fichaDetalhe.cliente.data_nascimento =  ficha.cliente.data_nascimento
            this.fichaDetalhe.cliente.data_nascimento_br =  ficha.cliente.data_nascimento_br
            this.fichaDetalhe.cliente.dt_ult_atu_confirme = ficha.cliente.dt_ult_atu_confirme
            this.fichaDetalhe.cliente.dt_ult_atu_portal = ficha.cliente.dt_ult_atu_portal
            this.fichaDetalhe.cliente.idade = ficha.cliente.idade
            this.fichaDetalhe.cliente.mae = ficha.cliente.mae
            this.fichaDetalhe.cliente.mae_cpf = ficha.cliente.mae_cpf
            this.fichaDetalhe.cliente.matricula = ficha.cliente.matricula
            this.fichaDetalhe.cliente.nome = ficha.cliente.nome
            this.fichaDetalhe.cliente.pai = ficha.cliente.pai
            this.fichaDetalhe.cliente.rg = ficha.cliente.rg
            this.fichaDetalhe.cliente.telefones_para_exibir =  ficha.cliente.telefones_para_exibir
            this.fichaDetalhe.cliente.emails_para_exibir =  ficha.cliente.emails_para_exibir
            this.fichaDetalhe.clientes_id =  ficha.clientes_id
            this.fichaDetalhe.status_atual.id = ficha.status_atual.id
            this.fichaDetalhe.status_atual.descricao =  ficha.status_atual.descricao
            this.fichaDetalhe.status_atual.motivo_ficha_ignorada = ficha.status_atual.motivo_ficha_ignorada
        },
        abreJanela(){
            this.abreJanelaForm = true
        },
        async abreFichaDetalhe(ficha, usuarioLogado){
            this.limpaCamposDaFicha()
            this.usuarioLogado = usuarioLogado
            this.preencheDadosFicha(ficha)
            this.tabsJanela.select('abaDadosPessoais')
            await this.registraUltimoAcesso()
            this.abreJanela()
        },
        fechaFichaDetalhe(){
            this.limpaCamposDaFicha()
            this.abreJanelaForm = false
        },
        eventoDeFechamentoDeJanela(){
            this.abreJanelaForm = false
            this.$emit('fechamentoJanelaFichaDetalhe')
        },
        atualizaAgendamentos(agendamentosSalvos){
            this.fichaDetalhe.agendamentos = agendamentosSalvos
        },
        registraUltimoAcesso(){
            let url = '/painel/fichas/registra/ultimo/acesso/'

            axios.get(url + this.fichaDetalhe.id).then(response => {}).catch(error => {});
        },

    }
}
</script>

<style lang="scss">
    div#modalFichaDetalhe.modal{
        width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
        top: 0 !important;
    }

    .tabs .tab a:focus, .tabs .tab a:focus.active {
        background-color: rgb(52 119 255 / 20%);
    }

    .tabs .tab a:hover{
        text-decoration: none;
    }
</style>

