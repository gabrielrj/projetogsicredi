<template>
    <div class="card-panel white">
        <section id="secaoDadosPessoais">
            <div class="row">
                <div class="input-field col m6 s12">
                    <input type="text" id="nome" name="nome" disabled v-model="ficha.cliente.nome">
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col m6 s12">
                    <input type="text" id="cpf" name="cpf" disabled v-model="ficha.cliente.cpf">
                    <label for="cpf">CPF</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col m6 s12">
                    <input type="text" id="matricula" name="matricula" disabled :value="ficha.cliente.matricula == null || ficha.cliente.matricula == '' ? 'N/a' : ficha.cliente.matricula">
                    <label for="matricula">Matrícula</label>
                </div>
                <div class="input-field col m6 s12">
                    <input type="text" id="rg" name="rg" disabled :value="ficha.cliente.rg == null || ficha.cliente.rg == '' ? 'N/a' : ficha.cliente.rg">
                    <label for="rg">RG</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col m6 s12">
                    <input type="text" id="data_nascimento" name="data_nascimento" disabled
                           :value="ficha.cliente.data_nascimento_br == null ? 'N/a' : (ficha.cliente.idade == null ? ficha.cliente.data_nascimento_br : ficha.cliente.data_nascimento_br + ' (' + ficha.cliente.idade + ' anos)')">
                    <label for="data_nascimento">Data de Nascimento</label>
                </div>
                <div class="input-field col m6 s12">
                    <input type="text" id="mae" name="mae" disabled :value="ficha.cliente.mae == null || ficha.cliente.mae == '' ? 'N/a' : ficha.cliente.mae">
                    <label for="mae">Mãe</label>
                </div>
            </div>
        </section>

        <section id="secaoDadosProfissionais">
            <div class="row">
                <div class="col s12">
                    <h5>Cargos (Matrículas)</h5>
                    <p v-if="ficha.cliente.cargos == null || ficha.cliente.cargos == undefined || ficha.cliente.cargos.length == 0"
                       class="label-info-nao-encontrada">Não foram encontradas informações de cargos(matrículas).</p>
                    <ul class="collapsible popout"
                        id="collapse_cargos"
                        v-else>
                        <li v-for="(cargo, cargo_i) in ficha.cliente.cargos">
                            <div class="collapsible-header">
                                <i class="material-icons">work</i>
                                <span>{{cargo.esfera + ' - ' + cargo.nome}}</span>
                            </div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col m6 s12">
                                        <input type="text" id="esfera" name="esfera" disabled
                                               :value="cargo.esfera">
                                        <label for="esfera">Esfera</label>
                                    </div>
                                    <div class="col m6 s12">
                                        <input type="text" id="classe" name="classe" disabled
                                               :value="cargo.classe == null || cargo.esfera == '' ? 'N/a' : cargo.esfera">
                                        <label for="classe">Classe</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m6 s12">
                                        <input type="text" id="cargo" name="cargo" disabled
                                               :value="cargo.nome">
                                        <label for="cargo">Cargo</label>
                                    </div>
                                    <div class="col m6 s12">
                                        <input type="text" id="situacao" name="situacao" disabled
                                               :value="cargo.situacao">
                                        <label for="situacao">Situação</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="secaoDadosBancarios">
            <div class="row">
                <div class="col s12">
                    <h5>Dados Bancários</h5>
                    <p v-if="ficha.cliente.dados_bancarios == null || ficha.cliente.dados_bancarios == undefined || ficha.cliente.dados_bancarios.length == 0"
                       class="label-info-nao-encontrada">Não foram encontradas informações de dados bancários.</p>
                    <ul class="collapsible popout"
                        id="collapse_dados_bancarios"
                        v-else>
                        <li v-for="(dados_bancarios, dados_bancarios_i) in ficha.cliente.dados_bancarios">
                            <div class="collapsible-header">
                                <i class="material-icons">account_balance</i>
                                <span>{{dados_bancarios.banco == null || dados_bancarios.nome == '' ? 'Banco' : dados_bancarios.banco}}</span>
                            </div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col s12">
                                        <input type="text" id="banco" name="banco" disabled
                                               :value="dados_bancarios.banco == null || dados_bancarios.banco == '' ? 'N/a' : dados_bancarios.banco">
                                        <label for="banco">Banco / Instituição financeira</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <input type="text" id="agencia" name="agencia" disabled
                                               :value="(dados_bancarios.conta != null ? replace(replace(dados_bancarios.conta, '(agência)', ''), '(conta)', '') : 'N/a')">
                                        <label for="agencia">Agência e Conta</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <input type="text" id="saldo_investido" name="saldo_investido" disabled
                                               v-money="{decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */}"
                                               :value="dados_bancarios.valor_investido != null ? dados_bancarios.valor_investido : '0,00'">
                                        <label for="saldo_investido">Valor investido</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m6 s12">
                                        <input type="text" id="valor_bruto" name="valor_bruto" disabled
                                               v-money="{decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */}"
                                               :value="dados_bancarios.valor_bruto != null ? dados_bancarios.valor_bruto : '0,00'">
                                        <label for="valor_bruto">Valor bruto</label>
                                    </div>
                                    <div class="col m6 s12">
                                        <input type="text" id="desconto" name="desconto" disabled
                                               v-money="{decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */}"
                                               :value="dados_bancarios.valor_desconto != null ? dados_bancarios.valor_desconto : '0,00'">
                                        <label for="desconto">Desconto</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m6 s12">
                                        <input type="text" id="valor_liquido" name="valor_liquido" disabled
                                               v-money="{decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */}"
                                               :value="dados_bancarios.valor_liquido != null ? dados_bancarios.valor_liquido : '0,00'">
                                        <label for="valor_liquido">Valor líquido</label>
                                    </div>
                                    <div class="col m6 s12">
                                        <input type="text" id="valor_margem" name="valor_margem" disabled
                                               v-money="{decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */}"
                                               :value="dados_bancarios.valor_margem != null ? dados_bancarios.valor_margem : '0,00'">
                                        <label for="valor_margem">Margem</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="secaoContatos">
            <h5>Contatos</h5>
            <div class="row">
                <div class="col m6 s12">
                    <div class="card-panel">
                        <h6>Telefones:</h6>
                        <br>
                        <p v-if="ficha.cliente.telefones_para_exibir.length == 0" class="label-info-nao-encontrada">Não foram encontrados telefones para esse cliente.</p>
                        <div v-else
                             v-for="(telefone_ficha, indice_tel) in ficha.cliente.telefones_para_exibir" style="height: 40px; margin-top: 2px;">
                            <span><i class="material-icons left">phone</i>{{telefone_ficha.numero}}</span>
                            <div class="right">
                                <span v-if="telefone_ficha.whatsapp == 1"><img src="/img/whatsapp_logo_20x20.png" title="Esse telefone é whatsapp"></span>&nbsp;
                                <span v-if="telefone_ficha.atualizado_confirmeonline == 1"><img src="/img/confirmeonline_logo_20x20.jpg" title="Esse telefone foi encontrado no confirme online"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col m6 s12">
                    <div class="card-panel">
                        <h6>E-mails:</h6>
                        <br>
                        <p v-if="ficha.cliente.emails_para_exibir.length == 0" class="label-info-nao-encontrada">Não foram encontrados e-mails para esse cliente.</p>
                        <div v-else
                             v-for="(email_ficha, indice_email) in ficha.cliente.emails_para_exibir" style="height: 40px; margin-top: 2px;">
                            <span><i class="material-icons left">email</i>{{email_ficha.email}}</span>
                            <div class="right">
                                <span v-if="email_ficha.atualizado_confirmeonline == 1"><img src="/img/confirmeonline_logo_20x20.jpg" title="Esse telefone foi encontrado no confirme online"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    name: "ficha_detalhe_dados_pessoais",
    props:{
        ficha: {
            type: Object,
            default: null,
        }
    },
    updated() {
        M.updateTextFields()
        $('#collapse_cargos').collapsible();
        $('#collapse_dados_bancarios').collapsible();
    },
    methods: {
        //Util
        replace(text, search, replace){
            return _.replace(text, search, replace)
        },
        //Util
    }
}
</script>

<style scoped>

</style>
