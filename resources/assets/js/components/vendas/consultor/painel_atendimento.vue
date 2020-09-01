<template>
    <div>
        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <div class="col s12">
                            <a href="/home" class="breadcrumb" title="Página Inicial"><i class="material-icons">home</i></a>
                            <a href="/home" class="breadcrumb">Página Inicial</a>
                            <a href="!#" class="breadcrumb">Painel do Consultor</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <md-app md-mode="reveal" md-scrollbar>
                    <md-app-toolbar class="blue darken-4 white-text">
                        <a href="#!" @click="menuVisivel = !menuVisivel"><i class="material-icons white-text">menu</i></a>

                        <span v-if="telaConsultorSelecionada == telaConsultor.DASHBOARD" class="md-title center">Dashboard</span>

                        <span v-else-if="telaConsultorSelecionada == telaConsultor.BUSCACPF" class="md-title center">Busca cliente por CPF</span>
                    </md-app-toolbar>

                    <md-app-drawer :md-active.sync="menuVisivel">
                        <md-toolbar class="md-transparent" md-elevation="0">Menu</md-toolbar>

                        <md-list>
                            <md-list-item @click="mudaTelaConsultor(telaConsultor.DASHBOARD)">
                                <i class="material-icons left">account_balance_wallet</i>
                                <span class="md-list-item-text"
                                      :class="{'label-bold-1' : telaConsultorSelecionada == telaConsultor.DASHBOARD}">Dashboard</span>
                            </md-list-item>

                            <md-list-item @click="mudaTelaConsultor(telaConsultor.BUSCACPF)">
                                <i class="material-icons left">search</i>
                                <span class="md-list-item-text"
                                      :class="{'label-bold-1' : telaConsultorSelecionada == telaConsultor.BUSCACPF}">Busca por CPF</span>
                            </md-list-item>
                        </md-list>
                    </md-app-drawer>

                    <md-app-content>
                        <vue-consultor-carteira ref="tela_carteira_consultor" v-if="telaConsultorSelecionada == telaConsultor.DASHBOARD"
                                                :usuario-logado="usuarioLogado"></vue-consultor-carteira>

                        <vue-consultor-busca-cpf v-if="telaConsultorSelecionada == telaConsultor.BUSCACPF"></vue-consultor-busca-cpf>
                    </md-app-content>
                </md-app>
            </div>
        </div>

        <vue-carteira-ficha-detalhe ref="ficha_detalhe" @fechamentoJanelaFichaDetalhe="fechamentoJanelaFichaDetalhe"></vue-carteira-ficha-detalhe>
    </div>
</template>

<script>

export default {
    name: "painel_atendimento",
    props: {
        usuarioLogadoJson: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            usuarioLogado: JSON.parse(this.usuarioLogadoJson),
            telaConsultor: {
                DASHBOARD: 'Dashboard',
                BUSCACPF: 'Busca por CPF'
            },
            telaConsultorSelecionada: null,
            menuVisivel: false,
        }
    },
    created() {
        this.telaConsultorSelecionada = this.telaConsultor.DASHBOARD
    },
    methods: {
        mudaTelaConsultor(telaConsultorSelecionada){
            this.menuVisivel = false
            this.telaConsultorSelecionada = telaConsultorSelecionada
        },
        fechamentoJanelaFichaDetalhe(){
            this.$refs.tela_carteira_consultor.retornaCarteiraCompleta()
        }
    }
}

</script>

<style lang="scss" scoped>
.md-app {
    //height: 100%;
    min-height: 700px;
    max-height: 100%;
    border: 1px solid rgba(black, .12);
}

/*Demo purposes only*/
.md-drawer {
    width: 230px;
    max-width: calc(100vw - 125px);
    background: whitesmoke;
}

.label-bold-1{
    font-weight: bold;
}
</style>
