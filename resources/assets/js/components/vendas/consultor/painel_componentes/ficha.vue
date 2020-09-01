<template>
    <div>
        <div :class="cssClass">
            <div class="card"
                 :class="retornaClasseCssDeCardDeAcordoComStatus(ficha)"
                 id="card_ficha_cliente">
                <div class="card-content black-text">
                    <div style="display: block">
                        <span class="card-title" style="display: inline;">{{ficha.cliente.nome}}</span>
                        <img v-if="ficha.cliente.atualizado_confirmeonline == 1"
                             class="right"
                             src="/img/confirmeonline_logo_40x40.jpg"
                             height="30" width="30"
                             title="Essa ficha tem dados atualizados no Confirme Online.">
                    </div>
                    <p><span>{{ficha.cliente.cpf}}</span></p><br>
                    <p style="font-weight: bold"><span>Cargos:</span></p>
                    <ul class="browser-default">
                        <li v-for="(cargo, cargo_i) in ficha.cliente.cargos">
                            <span>{{cargo.esfera + ' - ' + cargo.nome}}</span>
                        </li>
                    </ul>
                    <div class="chip">
                        <div style="display: block" class="center">
                            <!--i class="material-icons tiny">fiber_manual_record</i-->
                            <div class="circle-status"></div>
                            <span>{{ficha.status_atual.descricao}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <a class="btn btn-small waves-effect waves-light light-blue darken-4" @click="abreFichaDetalhe">
                        <i class="material-icons left">visibility</i>
                        Visualizar
                    </a>

                    <vue-ficha-ignora-cliente :ficha-id="ficha.id" @clienteIgnorado="fichaIgnorada"></vue-ficha-ignora-cliente>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ficha",
    props: {
        usuarioLogado: {
            type: Object,
            default: null,
        },
        ficha: {
            type: Object,
            default: null,
        },
        cssClass: {
            type: String,
            default: 'col xl4 l4 m6 s12',
        }
    },
    inject: ['carteira'],
    methods: {
        retornaClasseCssDeCardDeAcordoComStatus(ficha){
            if(ficha.status_atual.descricao == 'Em atendimento')
                return 'card-ficha-status-atendimento'
            else if(ficha.status_atual.descricao == 'Chamada agendada')
                return 'card-ficha-status-chamada-agendada'
            else if(ficha.status_atual.descricao == 'Visita agendada')
                return 'card-ficha-status-visita-agendada'
            else
                return 'card-ficha-status-disponivel'
        },

        abreFichaDetalhe(){
            if(this.ficha != null){
                let janelaFichaDetalhe = this.$root.$refs.painel_atendimento.$refs.ficha_detalhe
                janelaFichaDetalhe.abreFichaDetalhe(this.ficha, this.usuarioLogado)
            }
            else
                Swal.fire('Oops!', 'Não foi possível apresentar os detalhes da ficha.', 'error')
        },

        fichaIgnorada(){
            this.carteira.retornaCarteiraCompleta()
        }
    }
}
</script>

<style lang="scss">
.circle-status{
    height: 10px;
    width: 10px;
    border-radius: 50%;
    display: inline-block;
}

.card#card_ficha_cliente{
    border-radius: 5px;
}

.card#card_ficha_cliente:hover{
    box-shadow: 5px 5px 5px rgba(0,0,0,0.3);
}

.card-ficha-status-disponivel {
    border: 1px solid #bdbdbd;

    .chip {
        .circle-status {
            background-color: #424242;
        }
    }
}

.card-ficha-status-atendimento {
    border: 1px solid #e65100;

    .chip {
        .circle-status {
            background-color: #e65100;
        }
        span{

            color: #e65100;
        }
    }
}

.card-ficha-status-chamada-agendada {
    border: 1px solid #3D5AFE;

    .chip {
        .circle-status {
            background-color: #3D5AFE;
        }
        span{

            color: #3D5AFE;
        }
    }
}

.card-ficha-status-visita-agendada {
    border: 1px solid #4CAF50;

    .chip {
        .circle-status {
            background-color: #4CAF50;
        }
        span{

            color: #4CAF50;
        }
    }
}
</style>
