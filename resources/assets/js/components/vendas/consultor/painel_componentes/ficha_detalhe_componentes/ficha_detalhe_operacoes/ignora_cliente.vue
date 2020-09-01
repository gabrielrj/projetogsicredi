<template>
    <div style="display: inline-block;">
        <vue-loading :is-full-page="true" :loading="loading"></vue-loading>

        <a class="dropdown-trigger btn btn-small teal darken-4 waves-effect waves-light"
           href="#"
           :data-target="'ddOpcoesIgnorarCliente_ficha_' + fichaId">
            <i class="material-icons left">block</i>
            IGNORAR CLIENTE
        </a>

        <ul :id="'ddOpcoesIgnorarCliente_ficha_' + fichaId" class="dropdown-content">
            <li><a href="#!" codigo="VF" @click="ignoraCliente( 'VF')">Venda fechada</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="CF" @click="ignoraCliente('CF')">Cliente falecido</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" cogigo="CSI" @click="ignoraCliente('CSI')">Cliente sem interesse</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="CFOE" @click="ignoraCliente('CFOE')">Cliente fechou com outra empresa</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="CA" @click="ignoraCliente('CA')">Cliente ausente</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="DI" @click="ignoraCliente('DI')">Dados incorretos</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="BP" @click="ignoraCliente('BP')">Bloqueio/Procon</a></li>
            <li class="divider" tabindex="-1"></li>
            <li><a href="#!" codigo="O" @click="ignoraCliente('O')">Outros</a></li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "ignora_cliente",
    props: {
        fichaId: {
            type: Number,
            default: null,
        }
    },
    data() {
        return {
            loading: false,
        }
    },
    mounted() {
        $('.dropdown-trigger').dropdown();
    },
    methods: {
        //Ignorar cliente sem dar motivos detalhados
        ignoraCliente(codigo){
            //window.scrollTo( screen.width/2, screen.height/2 );
            if(this.fichaId == null){
                Swal.fire('Oops!', 'Infelizmente não foi possível ignorar esse cliente devido a um erro inesperado do sistema.', 'error')
                return
            }

            if(codigo == null || codigo == 'O')
                codigo = window.prompt('Especifíque um motivo para ignorar o cliente.')

            this.loading = true

            axios.post('/fichas/ignora', {'ficha_id': this.fichaId, 'motivo': codigo})
                .then((response) => {
                    this.loading = false
                    Swal.fire('Ótimo!', 'O cliente foi excluído da sua carteira.', 'success')
                    this.$emit('clienteIgnorado')
                })
                .catch((error) => {
                    this.loading = false
                    Swal.fire('Oops!', error.response.data.msg, 'error')
                })
        }
        //Ignorar cliente sem dar motivos detalhados
    }
}
</script>

<style scoped>

</style>
