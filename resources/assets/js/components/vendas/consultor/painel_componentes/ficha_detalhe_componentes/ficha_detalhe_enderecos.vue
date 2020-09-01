<template>
    <div class="card-panel" id="card_enderecos">
        <p v-if="ficha.cliente.enderecos.length == 0"
           class="label-info-nao-encontrada">Não foram encontrados enderecos para este cliente.</p>

        <ul v-else
            id="collapse_enderecos"
            class="collapsible popout">
            <li v-for="(endereco_cliente, endereco_cliente_i) in ficha.cliente.enderecos">
                <div class="collapsible-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>{{(endereco_cliente_i + 1) + 'º - ' + endereco_cliente.logradouro}}</span>

                    <img v-if="endereco_cliente.atualizado_confirmeonline == 1"
                         style="margin-left: auto;"
                         class="right"
                         src="/img/confirmeonline_logo_40x40.jpg"
                         height="25" width="25"
                         title="Essa ficha tem dados atualizados no Confirme Online.">
                </div>

                <div class="collapsible-body">
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <input type="text" id="cliente_cep" name="cliente_cep"
                                   disabled
                                   :value="endereco_cliente.cep == null || endereco_cliente.cep == '' ? 'N/a' : endereco_cliente.cep" />
                            <label for="cliente_cep">CEP</label>
                        </div>
                        <div class="input-field col m6 s12">
                            <input type="text" id="cliente_logradouro" name="cliente_logradouro"
                                   disabled
                                   :value="endereco_cliente.logradouro == null || endereco_cliente.logradouro == '' ? 'N/a' : endereco_cliente.logradouro" />
                            <label for="cliente_logradouro">Endereço</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col m6 s12">
                            <input type="text" id="cliente_cidade" name="cliente_cidade"
                                   disabled
                                   :value="endereco_cliente.cidade == null || endereco_cliente.cidade == '' ? 'N/a' : endereco_cliente.cidade" />
                            <label for="cliente_cidade">Cidade</label>
                        </div>
                        <div class="input-field col m6 s12">
                            <input type="text" id="cliente_uf" name="cliente_uf"
                                   disabled
                                   :value="endereco_cliente.uf == null || endereco_cliente.uf.sigla == '' ? 'N/a' : endereco_cliente.uf.sigla" />
                            <label for="cliente_uf">Estado (UF)</label>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "ficha_detalhe_enderecos",
    props:{
        ficha: {
            type: Object,
            default: null,
        }
    },
    updated() {
        $('#collapse_enderecos').collapsible();
    }
}
</script>

<style scoped>
    #card_enderecos {
        min-height: 700px;
    }
</style>
