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
                            <a href="!#" class="breadcrumb">Perfis</a>
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
                            <h4 class="col xl6 l6 m6 s12"><i class="fas fa-id-card small"></i>&nbsp; Lista de perfis cadastrados</h4>
                            <div class="col xl6 l6 m6 s12" v-if="empresaSelecionadaId != null && empresaSelecionadaId != 0">
                                <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Perfil"
                                   @click="cadastraNovoPerfil"><i class="material-icons left">add</i></a>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && retornaSeTemEmpresasCadastradas && Array.isArray(empresasOuEmpresa) && empresasOuEmpresa.length > 0">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">business</i>
                                <select name="sel_empresa" id="sel_empresa"
                                        @change="listaPerfisCadastrados"
                                        v-model="empresaSelecionadaId">
                                    <option value="" disabled>Selecione uma empresa para listar os perfis</option>
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
                                    md-description="Para poder cadastrar os perfis é necessário primeiro cadastrar uma empresa.">
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
                                    md-description="Infelizmente você não pode cadastrar nenhum perfil já que seu usuário não está associado a nenhuma empresa. Solicite ao seu administrador que o associe a alguma empresa.">
                                </md-empty-state>
                            </div>
                        </div>

                        <div v-else>
                            <md-empty-state
                                v-if="!loadingsAtivos && empresaSelecionada != null && empresaSelecionada != undefined && perfisCadastrados.length == 0"
                                md-icon="../img/svg/icons/id-card-solid.svg"
                                :md-label="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? 'Não há perfis cadastrados e nem é possível cadastrar novos perfis pois a empresa encontra-se inativa.' : 'Não há perfis cadastrados até o momento'"
                                :md-description="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? '' : 'Cadastre uma novo perfil para que possamos dar prosseguimento aos próximos cadastros.'">
                                <a class="btn indigo darken-2 waves-effect waves-light"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Perfil" @click="cadastraNovoPerfil">
                                    <i class="material-icons left">add</i>Cadastrar novo perfil
                                </a>
                            </md-empty-state>

                            <p v-else-if="loadingTextual" class="label-carregamento">Carregando perfis cadastrados...</p>

                            <div v-else-if="perfisCadastrados.length > 0">
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
                                                    <span>Perfil</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'nome')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyPerfil }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Funções de acesso</span>
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
                                            <tr v-for="(perfil, perfil_i) in listaFiltrada">
                                                <td v-if="usuarioLogado.super_user">
                                                    <span v-if="!perfil.empresa.ativo">
                                                        <strike><i>{{perfil.empresa.razao_social}}</i></strike>
                                                        &nbsp;&nbsp;
                                                        <span data-badge-caption=""
                                                              class="new badge red">Excluído</span>
                                                    </span>
                                                    <span v-else>{{perfil.empresa.razao_social}}</span>
                                                </td>
                                                <td><span>{{perfil.nome}}</span></td>
                                                <td>
                                                    <ul class="browser-default">
                                                        <li v-for="(funcao, funcao_i) in perfil.funcoes_de_acesso">
                                                            {{funcao.titulo}}
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td><span data-badge-caption=""
                                                          class="new badge"
                                                          :class="{'red' : perfil.ativo == 0, 'green' : perfil.ativo == 1}">{{perfil.ativo == 0 ? 'Excluído' : 'Ativo'}}</span>
                                                </td>
                                                <td><span>{{perfil.data_cadastro}}</span></td>
                                                <td v-if="empresaSelecionada != null && empresaSelecionada.ativo">
                                                    <div v-if="perfil.ativo">
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Alterar"
                                                           @click="editaPerfil(perfil)">
                                                            <i class="small material-icons grey-text text-darken-3">edit</i>
                                                        </a>
                                                        &nbsp;
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Excluir"
                                                           @click="deletaPerfil(perfil)">
                                                            <i class="small material-icons red-text">delete</i>
                                                        </a>
                                                    </div>
                                                    <a v-else
                                                       style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Reativar"
                                                       @click="reativaPerfil(perfil)">
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

        <vue-cadastra-edita-perfis ref="cadastra_edita_perfil"
                                   :funcoes-de-acesso="funcoesDeAcesso"
                                   @emissaoDeDadosSalvos="listaPerfisCadastrados"></vue-cadastra-edita-perfis>
    </div>
</template>

<script>

    export default {
        name: "lista_perfis",
        props: {
            usuarioLogadoJson: {
                type: String,
                default: null,
            },
            empresasOuEmpresaJson: {
                type: String,
                default: null,
            },
            funcoesDeAcessoJson: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                usuarioLogado: this.usuarioLogadoJson != null && this.usuarioLogadoJson != undefined && this.usuarioLogadoJson != '' ? JSON.parse(this.usuarioLogadoJson) : null,
                empresasOuEmpresa: this.empresasOuEmpresaJson != null && this.empresasOuEmpresaJson != undefined && this.empresasOuEmpresaJson != '' ? JSON.parse(this.empresasOuEmpresaJson) : null,
                funcoesDeAcesso:  this.funcoesDeAcessoJson != null && this.funcoesDeAcessoJson != undefined && this.funcoesDeAcessoJson != '' ? JSON.parse(this.funcoesDeAcessoJson) : null,
                perfisCadastrados: [],
                empresaSelecionadaId: null,
                empresaSelecionada: null,

                loading: false,
                loadingTextual: false,

                filtro: {
                    sortProperty: 'empresa.razao_social',
                    sortDirection: 'desc',
                    filterTerm: '',
                    sortPropertyEmpresa: true,
                    sortPropertyPerfil: false,
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

                self.filtro.listaOrdenada = _.orderBy(self.perfisCadastrados.filter(function (perfil) {
                    var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                    return (
                        searchRegex.test(perfil.empresa.razao_social) ||
                        searchRegex.test(perfil.nome) ||
                        searchRegex.test(perfil.ativo == 1 ? 'Ativo' : 'Inativo') ||
                        searchRegex.test(perfil.data_cadastro)
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
                    this.empresaSelecionada = _.find(this.empresasOuEmpresa, (item) =>  { return item.id === newValue})
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
                        this.listaPerfisCadastrados()
                    }
            },
            listaPerfisCadastrados() {
                this.$refs.alert.limpaMensagens()

                if(this.empresaSelecionadaId == null || this.empresaSelecionadaId == 0){
                    this.$refs.alert.abreMensagens({status_resposta: 'error', msg: 'É obrigatório selecionar uma empresa válida para listar os perfis cadastrados.'})
                    return
                }

                let request = {empresaId: this.empresaSelecionadaId, relacionamentos: ['empresa', 'funcoesDeAcesso.menu', 'funcoesDeAcesso.submenu']}

                this.loadingTextual = true

                axios.post('perfis/lista/por/empresa/json', request)
                    .then((response) => {
                        this.perfisCadastrados = response.data;
                        this.loadingTextual = false;
                    }).catch(error => {
                        if(this.usuarioLogado.super_user)
                            this.empresaSelecionadaId = null;

                        this.$refs.alert.abreMensagens(error.response);
                        this.loadingTextual = false;
                })
            },
            cadastraNovoPerfil(){
                if(this.empresaSelecionada == null || this.empresaSelecionada === 'undefined')
                    Swal.fire('Oops!', 'Não é possível cadastrar um perfil sem antes selecionar uma empresa.','error')
                else{
                    this.$refs.cadastra_edita_perfil.cadastraNovoPerfil(this.empresaSelecionada)
                }
            },
            editaPerfil(perfilParaEdicao){
                this.$refs.cadastra_edita_perfil.editaPerfil(perfilParaEdicao)
            },
            retornaSeTemEmpresasCadastradas(){
                return ((!Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa != null) || (Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa.length != 0))
            },
            deletaPerfil(perfilParaExclusao){
                if(confirm('Tem certeza que deseja excluir esse perfil? As equipes e cargos associados ao perfil serão excluídos também.')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.delete('/perfis/' + perfilParaExclusao.id).then(response => {
                        this.loading = false
                        this.listaPerfisCadastrados()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            reativaPerfil(perfilParaReativar){
                if(confirm('Tem certeza que deseja reativar esse perfil?')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.get('/perfis/reativa/' + perfilParaReativar.id).then(response => {
                        this.loading = false
                        this.listaPerfisCadastrados()
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
                        this.filtro.sortPropertyPerfil = value
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
