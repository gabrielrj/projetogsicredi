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
                            <a href="!#" class="breadcrumb">Departamentos</a>
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
                            <h4 class="col xl6 l6 m6 s12"><i class="material-icons small">device_hub</i>&nbsp;Lista de departamentos cadastrados</h4>
                            <div class="col xl6 l6 m6 s12" v-if="empresaSelecionadaId != null && empresaSelecionadaId != 0">
                                <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Departamento"
                                   @click="cadastraNovoDepartamento"><i class="material-icons left">add</i></a>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && retornaSeTemEmpresasCadastradas && Array.isArray(empresasOuEmpresa) && empresasOuEmpresa.length > 0">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">business</i>
                                <select name="sel_empresa" id="sel_empresa"
                                        @change="listaDepartamentosCadastrados"
                                        v-model="empresaSelecionadaId">
                                    <option value="" disabled>Selecione uma empresa para listar os departamentos</option>
                                    <option v-for="(empresa, empresa_i) in empresasOuEmpresa"
                                            :key="'empresa_' + empresa_i"
                                            :value="empresa.id"
                                            :title="empresa.razao_social">
                                        <!--strike v-if="!empresa.ativo">
                                            <i>{{empresa.razao_social}}</i>
                                        </strike-->
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
                                    md-description="Para poder cadastrar os departamentos é necessário primeiro cadastrar uma empresa.">
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
                                    md-description="Infelizmente você não pode cadastrar nenhum departamento já que seu usuário não está associado a nenhuma empresa. Solicite ao seu administrador que o associe a alguma empresa.">
                                </md-empty-state>
                            </div>
                        </div>

                        <div v-else>
                            <md-empty-state
                                v-if="!loadingsAtivos && empresaSelecionada != null && empresaSelecionada != undefined && departamentosCadastrados.length == 0"
                                md-icon="device_hub"
                                :md-label="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? 'Não há departamentos cadastrados e nem é possível cadastrar novos departamentos pois a empresa encontra-se inativa.' : 'Não há departamentos cadastrados até o momento'"
                                :md-description="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? '' : 'Cadastre uma novo departamento para que possamos dar prosseguimento aos próximos cadastros.'">
                                <a class="btn indigo darken-2 waves-effect waves-light"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Departamento" @click="cadastraNovoDepartamento">
                                    <i class="material-icons left">add</i>Cadastrar novo departamento
                                </a>
                            </md-empty-state>

                            <p v-else-if="loadingTextual" class="label-carregamento">Carregando departamentos cadastrados...</p>

                            <div v-else-if="departamentosCadastrados.length > 0">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix ">search</i>
                                        <input tipo="materialize"  id="search" type="text" class="validate" v-model="filtro.filterTerm">
                                        <label for="search">Pesquisar</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <table class="responsive-table striped">
                                            <thead>
                                            <tr>
                                                <th v-if="usuarioLogado.super_user">
                                                    <span>Empresa</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'empresa.razao_social')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyEmpresa }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Departamento</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'nome')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDepartamento }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Status</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'ativo')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyAtivo }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Data Cadastro</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'data_cadastro')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataCadastro }">sort</i>
                                                    </a>
                                                </th>
                                                <th v-if="empresaSelecionada != null && empresaSelecionada.ativo"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(departamento, departamento_i) in listaFiltrada">
                                                <td v-if="usuarioLogado.super_user">
                                                    <span v-if="!departamento.empresa.ativo">
                                                        <strike><i>{{departamento.empresa.razao_social}}</i></strike>
                                                        &nbsp;&nbsp;
                                                        <span data-badge-caption=""
                                                              class="new badge red">Excluído</span>
                                                    </span>
                                                    <span v-else>{{departamento.empresa.razao_social}}</span>
                                                </td>
                                                <td><span>{{departamento.nome}}</span></td>
                                                <td>
                                                    <span data-badge-caption=""
                                                          class="new badge"
                                                          :class="{'red' : departamento.ativo == 0, 'green' : departamento.ativo == 1}">{{departamento.ativo == 0 ? 'Excluído' : 'Ativo'}}</span>
                                                </td>
                                                <td><span>{{departamento.data_cadastro}}</span></td>
                                                <td v-if="empresaSelecionada != null && empresaSelecionada.ativo">
                                                    <div v-if="departamento.ativo">
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Alterar"
                                                           @click="editaDepartamento(departamento)">
                                                            <i class="small material-icons grey-text text-darken-3">edit</i>
                                                        </a>
                                                        &nbsp;
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Excluir"
                                                           @click="deletaDepartamento(departamento)">
                                                            <i class="small material-icons red-text">delete</i>
                                                        </a>
                                                    </div>
                                                    <a v-else
                                                       style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Reativar"
                                                       @click="reativaDepartamento(departamento)">
                                                        <i class="small material-icons blue-text text-darken-2">restore</i>
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

        <vue-cadastra-edita-departamentos ref="cadastra_edita_departamento" @emissaoDeDadosSalvos="listaDepartamentosCadastrados"></vue-cadastra-edita-departamentos>
    </div>
</template>

<script>

    export default {
        name: "lista_departamentos",
        props: {
            usuarioLogadoJson: {
                type: String,
                default: null,
            },
            empresasOuEmpresaJson: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                usuarioLogado: this.usuarioLogadoJson != null && this.usuarioLogadoJson != undefined && this.usuarioLogadoJson != '' ? JSON.parse(this.usuarioLogadoJson) : null,
                empresasOuEmpresa: this.empresasOuEmpresaJson != null && this.empresasOuEmpresaJson != undefined && this.empresasOuEmpresaJson != '' ? JSON.parse(this.empresasOuEmpresaJson) : null,
                departamentosCadastrados: [],
                empresaSelecionadaId: null,
                empresaSelecionada: null,

                loading: false,
                loadingTextual: false,

                filtro: {
                    sortProperty: 'empresa.razao_social',
                    sortDirection: 'desc',
                    filterTerm: '',
                    sortPropertyEmpresa: true,
                    sortPropertyDepartamento: false,
                    sortPropertyAtivo: false,
                    sortPropertyDataCadastro: false,
                    listaOrdenada: [],

                },
                paginacao: {
                    regsPorPage: 5,
                    totalPages: Number,
                    totalRegs: Number,
                    currentPage: 1,
                }
            }
        },
        computed: {
            listaFiltrada: function () {
                var self = this

                self.filtro.listaOrdenada = _.orderBy(self.departamentosCadastrados.filter(function (departamento) {
                    var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                    return (
                        searchRegex.test(departamento.empresa.razao_social) ||
                        searchRegex.test(departamento.nome) ||
                        searchRegex.test(departamento.ativo == 1 ? 'Ativo' : 'Inativo') ||
                        searchRegex.test(departamento.data_cadastro)
                    )
                }), this.filtro.sortProperty, this.filtro.sortDirection)

                this.paginacao.totalRegs = _.size(this.filtro.listaOrdenada)
                this.paginacao.totalPages = (this.paginacao.totalRegs > this.filtro.regsPorPage) ? _.floor(_.divide(this.paginacao.totalRegs, this.paginacao.regsPorPage)) : 1
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
                        this.filtro.listaOrdenada = _.slice(this.filtro.listaOrdenada, 0, (this.paginacao.currentPage * this.paginacao.regsPorPage))
                    else
                        this.filtro.listaOrdenada = _.slice(this.filtro.listaOrdenada, ((this.paginacao.currentPage - 1) * this.paginacao.regsPorPage), (this.paginacao.currentPage * this.paginacao.regsPorPage))
                }

                return this.filtro.listaOrdenada

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
            $('#sel_empresa').formSelect()
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
                        this.listaDepartamentosCadastrados()
                    }
            },
            listaDepartamentosCadastrados() {
                this.$refs.alert.limpaMensagens()

                if(this.empresaSelecionadaId == null || this.empresaSelecionadaId == 0){
                    this.$refs.alert.abreMensagens({status_resposta: 'error', msg: 'É obrigatório selecionar uma empresa válida para listar os departamentos cadastrados.'})
                    return
                }

                let request = {empresaId: this.empresaSelecionadaId, relacionamentos: ['empresa']}

                this.loadingTextual = true

                axios.post('departamentos/lista/por/empresa/json', request)
                    .then((response) => {
                        this.departamentosCadastrados = response.data;
                        this.loadingTextual = false;
                    }).catch(error => {
                        if(this.usuarioLogado.super_user)
                            this.empresaSelecionadaId = null;

                        this.$refs.alert.abreMensagens(error.response);
                        this.loadingTextual = false;
                    })
            },
            cadastraNovoDepartamento(){
                if(this.empresaSelecionada == null || this.empresaSelecionada === 'undefined')
                    Swal.fire('Oops!', 'Não é possível cadastrar um departamento sem antes selecionar uma empresa.','error')
                else{
                    this.$refs.cadastra_edita_departamento.cadastraNovoDepartamento(this.empresaSelecionada)
                }
            },
            editaDepartamento(departamentoParaEdicao){
                this.$refs.cadastra_edita_departamento.editaDepartamento(departamentoParaEdicao)
            },
            retornaSeTemEmpresasCadastradas(){
                return ((!Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa != null) || (Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa.length != 0))
            },
            deletaDepartamento(departamentoParaExclusao){
                if(confirm('Tem certeza que deseja excluir esse departamento? As equipes e cargos associados ao departamento serão excluídos também.')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.delete('/departamentos/' + departamentoParaExclusao.id).then(response => {
                        this.loading = false
                        this.listaDepartamentosCadastrados()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            reativaDepartamento(departamentoParaReativar){
                if(confirm('Tem certeza que deseja reativar esse departamento?')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.get('/departamentos/reativa/' + departamentoParaReativar.id).then(response => {
                        this.loading = false
                        this.listaDepartamentosCadastrados()
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
            toggleOrder(ev, field) {
                ev.preventDefault()
                if (this.filtro.sortProperty == field) {
                    if (this.filtro.sortDirection == 'asc')
                        this.filtro.sortDirection = 'desc'
                    else
                        this.filtro.sortDirection = 'asc'
                } else {
                    this.iconsColor(this.filtro.sortProperty, false)
                    this.iconsColor(field, true)
                    this.filtro.sortProperty = field
                    this.filtro.sortDirection = 'asc'
                }
            },
            iconsColor(field, value) {
                switch (field) {
                    case 'empresa.razao_social':
                        this.filtro.sortPropertyEmpresa = value
                        break;
                    case 'nome':
                        this.filtro.sortPropertyDepartamento = value
                        break;
                    case 'ativo':
                        this.filtro.sortPropertyAtivo = value
                        break;
                    case 'data_cadastro':
                        this.filtro.sortPropertyDataCadastro = value
                        break;
                    default:
                }
            },
        }
    }
</script>

<style scoped>

</style>
