<template>
    <div>
        <vue-alert ref="alert"></vue-alert>

        <div class="row" v-if="loading">
            <div class="col s12">
                <p class="label-carregamento">Carregando sua carteira de clientes...</p>
            </div>
        </div>

        <div class="row" v-else-if="carteiraDoConsultorLogado == null">
            <div class="col s12">
                <p class="label-info-nao-encontrada">Não foi possível carregar sua carteira de clientes. Informe esse problema ao administrador do sistema.</p>
            </div>
        </div>

        <div class="row" v-else>
            <vue-carteira-ficha v-for="(ficha, ficha_indice) in carteiraDoConsultorLogado.fichas_ordenadas_ultimo_acesso"
                                :key="'ficha_indice_' + ficha_indice"
                                css-class="col xl4 l4 m6 s12"
                                :usuario-logado="usuarioLogado"
                                :ficha.sync="ficha"></vue-carteira-ficha>
        </div>

        <vue-loading :loading="loading" :is-full-page="false"></vue-loading>

    </div>
</template>

<script>
export default {
    name: "carteira",
    props: {
        usuarioLogado: {
            type: Object,
            default: null,
        },
    },
    provide: function () {
        return {
            carteira: this
        }
    },
    data(){
        return{
            carteiraDoConsultorLogado: null,
            loading: false,
        }
    },
    mounted() {
        this.inicializaTela()
    },
    methods: {
        inicializaTela(){
            this.loading = true

            this.retornaCarteiraCompleta()
        },
        retornaCarteiraCompleta(){
            this.$refs.alert.limpaMensagens()

            axios.get('/carteira/retorna/carteira/ativa').then(response => {
                this.loading = false
                this.carteiraDoConsultorLogado = response.data
            }).catch(error => {
                this.$refs.alert.abreMensagens(error.response);
                this.loading = false
            });
        },

    }

}
</script>

<style scoped></style>
