<template>
    <div class="card-panel" id="card_emprestimos">
        <p v-if="ficha.cliente.emprestimos.length == 0"
           class="label-info-nao-encontrada">Não foram encontrados empréstimos realizados para este cliente.</p>

        <ul v-else
            id="collapse_emprestimos"
            class="collapsible popout">
            <li v-for="(emprestimo, emprestimo_i) in ficha.cliente.emprestimos">
                <div class="collapsible-header">
                    <i class="material-icons">account_balance</i>
                    <span v-if="emprestimo.banco != null && emprestimo.banco != ''">{{ !emprestimo.banco.includes('BANCO') ? 'BANCO ' + emprestimo.banco : emprestimo.banco }}</span>
                    <span v-else class="label-info-nao-encontrada">Não há a informação do banco</span>
                    <span v-if="emprestimo.valor_total != null && emprestimo.valor_total != ''"
                          class="new badge right"
                          data-badge-caption="">{{ emprestimo.valor_total }}</span>
                </div>

                <div class="collapsible-body">
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="emprestimo_banco" name="emprestimo_banco"
                                   disabled
                                   :value="emprestimo.banco == null || emprestimo.banco == '' ? 'N/a' : emprestimo.banco" />
                            <label for="emprestimo_banco">Banco / Instituição financeira</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m6 s12">
                            <input type="text" id="emprestimo_parcela" name="emprestimo_parcela"
                                   disabled
                                   :value="emprestimo.valor_parcela_formatado == null || emprestimo.valor_parcela_formatado == '' ? 'N/a' : emprestimo.valor_parcela_formatado" />
                            <label for="emprestimo_parcela">Parcela</label>
                        </div>
                        <div class="input-field col m6 s12">
                            <input type="text" id="emprestimo_total" name="emprestimo_total"
                                   disabled
                                   :value="emprestimo.valor_total == null || emprestimo.valor_total == '' ? 'N/a' : emprestimo.valor_total" />
                            <label for="emprestimo_total">Total</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col m4 s12">
                            <input type="text" id="emprestimo_parcelas_totais" name="emprestimo_parcelas_totais"
                                   disabled
                                   :value="emprestimo.quantidade_parcelas_total == null || emprestimo.quantidade_parcelas_total == '' ? 'N/a' : emprestimo.quantidade_parcelas_total" />
                            <label for="emprestimo_parcelas_totais">Total de parcelas</label>
                        </div>
                        <div class="input-field col m4 s12">
                            <input type="text" id="emprestimo_parcelas_restantes" name="emprestimo_parcelas_restantes"
                                   disabled
                                   :value="emprestimo.quantidade_parcelas_restantes == null || emprestimo.quantidade_parcelas_restantes == '' ? 'N/a' : emprestimo.quantidade_parcelas_restantes" />
                            <label for="emprestimo_parcelas_restantes">Parcelas restantes</label>
                        </div>
                        <div class="input-field col m4 s12">
                            <input type="text" id="emprestimo_prazo" name="emprestimo_prazo"
                                   disabled
                                   :value="emprestimo.prazo == null || emprestimo.prazo == '' ? 'N/a' : emprestimo.prazo" />
                            <label for="emprestimo_prazo">Prazo</label>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "ficha_detalhe_emprestimos",
    props:{
        ficha: {
            type: Object,
            default: null,
        }
    },
    updated() {
        $('#collapse_emprestimos').collapsible();
    }
}
</script>

<style scoped>
    #card_emprestimos {
        min-height: 700px;
    }
</style>
