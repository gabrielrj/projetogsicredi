<template>
    <div>
        <vue-loading :loading="loading" :is-full-page="true"></vue-loading>

        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <div class="col s12">
                            <a href="/home" class="breadcrumb" title="Página Inicial"><i class="material-icons">home</i></a>
                            <a href="/home" class="breadcrumb">Página Inicial</a>
                            <span class="breadcrumb">Painel do Gestor</span>
                            <a href="!#" class="breadcrumb">Filtros</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <vue-alert ref="alert"></vue-alert>

                <div class="card-panel">
                    <section>
                        <div class="row">
                            <h4 class="col xl6 l6 m6 s12"><i class="material-icons small">filter_list</i>&nbsp;Lista de filtros cadastrados</h4>
                            <div class="col xl6 l6 m6 s12" v-if="empresaSelecionadaId != null && empresaSelecionadaId != 0">
                                <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Filtro"
                                   @click="cadastraNovoFiltro"><i class="material-icons left">add</i></a>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && retornaSeTemEmpresasCadastradas && Array.isArray(empresasOuEmpresa) && empresasOuEmpresa.length > 0">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">business</i>
                                <select name="sel_empresa" id="sel_empresa"
                                        @change="listaFiltrosCadastrados"
                                        v-model="empresaSelecionadaId">
                                    <option value="" disabled>Selecione uma empresa para listar os filtros</option>
                                    <option v-for="(empresa, empresa_i) in empresasOuEmpresa"
                                            :key="'empresa_' + empresa_i"
                                            :value="empresa.id"
                                            :title="empresa.razao_social">
                                        <span v-if="!empresa.ativo">
                                            <strike><i>{{empresa.razao_social}}</i></strike>
                                            <span data-badge-caption=""
                                                  class="new badge red">Excluído</span>
                                        </span>

                                        <span v-else>{{empresa.razao_social}}</span>
                                    </option>
                                </select>
                                <label>Selecione a empresa</label>
                            </div>
                        </div>

                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && !retornaSeTemEmpresasCadastradas">
                            <div class="col s12">
                                <md-empty-state
                                    md-rounded
                                    class="red"
                                    md-icon="error"
                                    md-label="Desculpe! Não é possível continuar a operação"
                                    md-description="Para poder cadastrar os filtros é necessário primeiro cadastrar uma empresa.">
                                </md-empty-state>
                            </div>
                        </div>

                        <div class="row" v-else-if="!usuarioLogado.perfil.super_user == 1 && !retornaSeTemEmpresasCadastradas">
                            <div class="col s12">
                                <md-empty-state
                                    md-rounded
                                    class="red"
                                    md-icon="error"
                                    md-label="Desculpe! Não é possível continuar a operação"
                                    md-description="Infelizmente você não pode cadastrar nenhum filtro já que seu usuário não está associado a nenhuma empresa. Solicite ao seu administrador que o associe a alguma empresa.">
                                </md-empty-state>
                            </div>
                        </div>

                        <div v-else>
                            <md-empty-state
                                v-if="!loadingsAtivos && empresaSelecionada != null && empresaSelecionada != undefined && filtrosCadastrados.length == 0"
                                md-icon="filter_list"
                                :md-label="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? 'Não há filtros cadastrados e nem é possível cadastrar novos filtros pois a empresa encontra-se inativa.' : 'Não há filtros cadastrados até o momento'"
                                :md-description="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? '' : 'Cadastre uma novo filtro para que possamos dar prosseguimento aos próximos cadastros.'">
                                <a class="btn indigo darken-2 waves-effect waves-light"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Filtro" @click="cadastraNovoFiltro">
                                    <i class="material-icons left">add</i>Cadastrar novo filtro
                                </a>
                            </md-empty-state>

                            <p v-else-if="loadingTextual" class="label-carregamento">Carregando filtros cadastrados...</p>

                            <div v-else-if="filtrosCadastrados.length > 0">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix ">search</i>
                                        <input tipo="materialize"  id="search" type="text" class="validate" v-model="filtro_tela.filterTerm">
                                        <label for="search">Pesquisar</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <table class="responsive-table striped">
                                            <thead>
                                            <tr>
                                                <th v-if="usuarioLogado.super_user">
                                                    Empresa
                                                </th>
                                                <th>
                                                    Destino do filtro
                                                </th>
                                                <th>
                                                    Idade
                                                </th>
                                                <th>
                                                    Renda mínima
                                                </th>
                                                <th>
                                                    Margem mínima
                                                </th>
                                                <th>
                                                    Parcela mínima
                                                </th>
                                                <th class="center">
                                                    Matrículas
                                                </th>
                                                <th class="center">
                                                    Regiões
                                                </th>
                                                <th>
                                                    Bancos Recebimento
                                                </th>
                                                <th>
                                                    Bancos Empréstimo
                                                </th>
                                                <th>
                                                    Total de fichas filtradas
                                                </th>
                                                <th>
                                                    Data de Cadastro
                                                </th>
                                                <th>
                                                    Status
                                                </th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(filtro, filtro_i) in listaFiltrada">
                                                <td v-if="usuarioLogado.super_user"><span>{{filtro.empresa.razao_social}}</span></td>
                                                <td><span>{{filtro.para_quem_foi_aplicado_filtro}}</span></td>
                                                <td><span>{{filtro.filtro_idade}}</span></td>
                                                <td><span>{{filtro.renda_formatada}}</span></td>
                                                <td><span>{{filtro.margem_formatada}}</span></td>
                                                <td><span>{{filtro.parcela_formatada}}</span></td>
                                                <td class="center">
                                                    <ul class="browser-default" v-if="filtro.filtro_cargos.length > 0">
                                                        <li v-for="(filtro_cargo, cargo_i) in filtro.filtro_cargos">
                                                            <span v-if="filtro_cargo.cargo == null">{{filtro_cargo.esfera}} - Todos os cargos</span>
                                                            <span v-else-if="filtro_cargo.situacao == null">{{filtro_cargo.esfera + ' - ' + filtro_cargo.cargo}} - Todas as situações</span>
                                                            <span v-else>{{filtro_cargo.esfera + ' - ' + filtro_cargo.cargo + ' - ' + filtro_cargo.situacao}}</span>
                                                        </li>
                                                    </ul>
                                                    <span v-else>N/a</span>
                                                </td>
                                                <td class="center">
                                                    <ul class="browser-default" v-if="filtro.filtro_cidades.length > 0">
                                                        <li v-for="(filtro_cidade, cidade_i) in filtro.filtro_cidades">
                                                            <span v-if="filtro_cidade.cidade == null || filtro_cidade.cidade == ''">{{filtro_cidade.estado.sigla}} - Todas as cidades</span>
                                                            <span v-else>{{filtro_cidade.estado.sigla + ' - ' + filtro_cidade.cidade}}</span>
                                                        </li>
                                                    </ul>
                                                    <span v-else>N/a</span>
                                                </td>
                                                <td>
                                                    <ul class="browser-default" v-if="filtro.filtro_bancos_recs.length > 0">
                                                        <li v-for="(filtro_banco, banco_i) in filtro.filtro_bancos_recs">
                                                            <span>{{filtro_banco.banco}}</span>
                                                        </li>
                                                    </ul>
                                                    <span v-else>N/a</span>
                                                </td>
                                                <td>
                                                    <ul class="browser-default" v-if="filtro.filtro_bancos_emps.length > 0">
                                                        <li v-for="(filtro_banco, banco_i) in filtro.filtro_bancos_emps">
                                                            <div class="chip">
                                                                <span>{{filtro_banco.banco}}</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <span v-else>N/a</span>
                                                </td>
                                                <td>
                                                    <span>{{filtro.total_fichas_filtradas}}</span>
                                                </td>
                                                <td>
                                                    <span>{{filtro.data_cadastro_filtro}}</span>
                                                </td>
                                                <td>
                                                    <span data-badge-caption=""
                                                          class="new badge"
                                                          :class="{'red' : filtro.ativo == 0, 'green' : filtro.ativo == 1}">{{filtro.ativo == 0 ? 'Excluído' : 'Ativo'}}</span>
                                                </td>
                                                <td>
                                                    <a class="red-text" style="cursor: pointer;"
                                                       title="Excluir"
                                                       v-if="filtro.ativo"
                                                       @click="deletaFiltro(filtro)">
                                                        <i class="material-icons left">delete</i>
                                                    </a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <div class="left">
                                            <strong>Total de registros por pagina</strong>
                                            <select name="total_regs_pag"
                                                    id="total_regs_pag"
                                                    v-model="paginacao.regsPorPage">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col s12">
                                        <div class="center">
                                            <vue-paginate :totalPage="paginacao.totalPages"
                                                          v-model="paginacao.currentPage"
                                                          nextText="Próximo"
                                                          prevText="Anterior"
                                                          :withNextPrev="true"
                                                          :customActiveBGColor="'#000099'"
                                                          @btnClick="mudaPagina"></vue-paginate>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </section>

                </div>
            </div>
        </div>

        <vue-gestor-cadastra-filtros ref="cadastra_edita_filtro"
                                     :cargos="cargos"
                                     :estados-e-cidades="estadosECidades"
                                     :bancos-recebimento="bancosRecebimento"
                                     :bancos-emprestimo="bancosEmprestimo"
                                     :empresa="empresaSelecionada"
                                     :usuario-logado="usuarioLogado"
                                     @emissaoDeDadosSalvos="listaFiltrosCadastrados"></vue-gestor-cadastra-filtros>

    </div>
</template>

<script>
export default {
    name: "lista_filtros",
    props: {
        usuarioLogadoJson: {
            type: String,
            default: null,
        },
        empresasOuEmpresaJson: {
            type: String,
            default: null,
        },
        cargosJson: {
            type: String,
            default: null,
        },
        estadosECidadesJson: {
            type: String,
            default: null,
        },
        bancosRecebimentoJson: {
            type: String,
            default: null,
        },
        bancosEmprestimoJson: {
            type: String,
            default: null,
        }
    },
    data() {
        return {
            usuarioLogado: this.usuarioLogadoJson != null && this.usuarioLogadoJson != undefined && this.usuarioLogadoJson != '' ? JSON.parse(this.usuarioLogadoJson) : null,
            empresasOuEmpresa: this.empresasOuEmpresaJson != null && this.empresasOuEmpresaJson != undefined && this.empresasOuEmpresaJson != '' ? JSON.parse(this.empresasOuEmpresaJson) : null,
            cargos: this.cargosJson != null && this.cargosJson != undefined && this.cargosJson != '' ? JSON.parse(this.cargosJson) : null,
            estadosECidades: this.estadosECidadesJson != null && this.estadosECidadesJson != undefined && this.estadosECidadesJson != '' ? JSON.parse(this.estadosECidadesJson) : null,
            bancosRecebimento: this.bancosRecebimentoJson != null && this.bancosRecebimentoJson != undefined && this.bancosRecebimentoJson != '' ? JSON.parse(this.bancosRecebimentoJson) : null,
            bancosEmprestimo: this.bancosEmprestimoJson != null && this.bancosEmprestimoJson != undefined && this.bancosEmprestimoJson != '' ? JSON.parse(this.bancosEmprestimoJson) : null,
            filtrosCadastrados: [],
            empresaSelecionadaId: null,
            empresaSelecionada: null,

            filtro_tela: {
                filterTerm: '',
                listaOrdenada: [],
            },
            paginacao: {
                regsPorPage: 5,
                totalPages: Number,
                totalRegs: Number,
                currentPage: 1,
            },

            loading: false,
            loadingTextual: false,
        }
    },
    computed: {
        listaFiltrada: function () {
            var self = this

            self.filtro_tela.listaOrdenada = this.filtrosCadastrados.filter(function (filtro) {
                var searchRegex = new RegExp(self.filtro_tela.filterTerm, 'i')
                return (
                    searchRegex.test(filtro.empresa.razao_social) ||
                    searchRegex.test(filtro.para_quem_foi_aplicado_filtro) ||
                    searchRegex.test(filtro.filtro_idade) ||
                    searchRegex.test(filtro.renda_formatada) ||
                    searchRegex.test(filtro.margem_formatada) ||
                    searchRegex.test(filtro.parcela_formatada) ||
                    searchRegex.test(filtro.ativo ? 'Ativo' : 'Inativo') ||
                    searchRegex.test(filtro.data_cadastro_filtro)
                )
            })

            this.paginacao.totalRegs = _.size(this.filtro_tela.listaOrdenada)
            this.paginacao.totalPages = (this.paginacao.totalRegs > this.filtro_tela.regsPorPage) ? _.floor(_.divide(this.paginacao.totalRegs, this.paginacao.regsPorPage)) : 1
            let restoDaDivisao = (this.paginacao.totalRegs % this.paginacao.regsPorPage)

            if(this.paginacao.totalRegs > this.paginacao.regsPorPage){
                if(restoDaDivisao != 0)
                    this.paginacao.totalPages++
            }else {
                this.paginacao.currentPage = 1
            }

            if(this.paginacao.currentPage > this.paginacao.totalPages)
                this.paginacao.currentPage = 1

            if(this.paginacao.totalRegs > 0){
                if(this.paginacao.currentPage == 1)
                    this.filtro_tela.listaOrdenada = _.slice(this.filtro_tela.listaOrdenada, 0, (this.paginacao.currentPage * this.paginacao.regsPorPage))
                else
                    this.filtro_tela.listaOrdenada = _.slice(this.filtro_tela.listaOrdenada, ((this.paginacao.currentPage - 1) * this.paginacao.regsPorPage), (this.paginacao.currentPage * this.paginacao.regsPorPage))
            }

            return this.filtro_tela.listaOrdenada

        },

        loadingsAtivos(){
            return (this.loadingTextual || this.loading)
        }
    },
    watch: {
        empresaSelecionadaId: function (newValue) {
            if(newValue == null || newValue === 0)
                this.empresaSelecionada = null
            else
            if(Array.isArray(this.empresasOuEmpresa))
                this.empresaSelecionada = _.find(this.empresasOuEmpresa, (item) =>  { return item.id === newValue })
            else
                this.empresaSelecionada = this.empresasOuEmpresa
        }
    },
    updated() {
        $('#total_regs_pag').formSelect()
    },
    mounted() {
        this.inicializaLista()
    },
    methods: {
        inicializaLista(){
            if(this.retornaSeTemEmpresasCadastradas() && !Array.isArray(this.empresasOuEmpresa))
                if(!this.usuarioLogado.perfil.super_user == 1) {
                    this.empresaSelecionadaId = this.empresasOuEmpresa.id
                    this.listaFiltrosCadastrados()
                }
        },
        listaFiltrosCadastrados() {
            this.$refs.alert.limpaMensagens()

            if(this.empresaSelecionadaId == null || this.empresaSelecionadaId == 0){
                this.$refs.alert.abreMensagens({status_resposta: 'error', msg: 'É obrigatório selecionar uma empresa válida para listar os filtros cadastrados.'})
                return
            }

            let request = {
                empresaId: this.empresaSelecionadaId,
                relacionamentos: [
                    'filtro_cargos',
                    'filtro_cidades.estado',
                    'filtro_bancos_recs',
                    'filtro_bancos_emps',
                    'usuario.funcionario.equipe',
                    'usuario.funcionario.empresa',
                    'equipe',
                ],
                ordenacao: [
                    'created_at',
                    'desc',
                ]
            }

            this.loadingTextual = true

            axios.post('configuracaofiltros/lista/por/empresa/json', request)
                .then((response) => {
                    this.filtrosCadastrados = response.data;
                    this.loadingTextual = false;
                }).catch(error => {

                    if(this.usuarioLogado.super_user == 1)
                        this.empresaSelecionadaId = null;

                this.$refs.alert.abreMensagens(error.response);
                this.loadingTextual = false;
            })
        },
        cadastraNovoFiltro(){
            if(this.empresaSelecionada == null || this.empresaSelecionada === 'undefined')
                Swal.fire('Oops!', 'Não é possível cadastrar um filtro sem antes selecionar uma empresa.','error')
            else{
                this.$refs.cadastra_edita_filtro.cadastraNovoFiltro()
            }
        },
        editaFiltro(filtroParaEdicao){
            this.$refs.cadastra_edita_filtro.editaFiltro(filtroParaEdicao)
        },
        retornaSeTemEmpresasCadastradas(){
            return ((!Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa != null) || (Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa.length != 0))
        },
        deletaFiltro(filtroParaExclusao){
            if(confirm('Tem certeza que deseja excluir esse filtro?')){
                this.$refs.alert.limpaMensagens()
                this.loading = true

                axios.delete('/configuracaofiltros/' + filtroParaExclusao.id).then(response => {
                    this.loading = false
                    this.listaFiltrosCadastrados()
                    this.$refs.alert.abreMensagens(response)
                    Swal.fire('Ótimo', response.data.msg, 'success')
                }).catch(error => {
                    this.loading = false
                    this.$refs.alert.abreMensagens(error.response)
                    Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                })
            }
        },

        mudaPagina: function(page){
            this.paginacao.currentPage = page
        },

    }
}
</script>

<style lang="scss">

.card#card_filtro{
    border-radius: 5px;
}

.card#card_filtro:hover{
    box-shadow: 5px 5px 5px rgba(0,0,0,0.3);
}

</style>
