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
                            <a href="!#" class="breadcrumb">Empresas</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <vue-alert ref="alert"></vue-alert>

                <md-empty-state
                    v-if="empresasCadastradas.length == 0"
                    md-icon="business"
                    md-label="Não há empresas cadastradas até o momento"
                    md-description="Cadastre uma nova empresa para que possamos dar prosseguimento aos próximos cadastros.">
                    <a class="btn indigo darken-2 waves-effect waves-light"
                       title="Cadastrar Nova Empresa" @click="cadastraNovaEmpresa"><i class="material-icons left">add</i>Cadastrar nova empresa</a>
                </md-empty-state>

                <div v-else class="card-panel">
                    <section>
                        <div class="row">
                            <h4 class="col xl6 l6 m6 s12"><i class="material-icons small">business</i>&nbsp;Lista de empresas cadastradas</h4>
                            <div class="col xl6 l6 m6 s12">
                                <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                                   title="Cadastrar Nova Empresa" @click="cadastraNovaEmpresa"><i class="material-icons left">add</i></a>
                            </div>
                        </div>
                    </section>
                    <div class="divider"></div>
                    <section>
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
                                        <th>
                                            <span>Id</span>
                                            <a class="left" href="#" @click="toggleOrder($event, 'id')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyId }">sort</i>
                                            </a>
                                        </th>
                                        <th>
                                            <span>CPF/CNPJ</span>
                                            <a class="left" href="#" @click="toggleOrder($event, 'cpf_cnpj')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyCPFCNPJ }">sort</i>
                                            </a>
                                        </th>
                                        <th>
                                            <span>Empresa</span>
                                            <a class="left" href="#" @click="toggleOrder($event, 'razao_social')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyRazaoSocial }">sort</i>
                                            </a>
                                        </th>
                                        <th>
                                            <span>Total de Licenças</span>
                                            <a class="left" href="#" @click="toggleOrder($event, 'quantidade_licencas')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyTotalLicencas }">sort</i>
                                            </a>
                                        </th>
                                        <th><span>Usuários</span></th>
                                        <!--th>
                                            Usuários ativos
                                            <a class="left" href="#" @click="toggleOrder($event, 'quantidade_usuarios_ativos')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyUsuariosAtivos }">sort</i>
                                            </a>
                                        </th>
                                        <th>
                                            Total de usuários
                                            <a class="left" href="#" @click="toggleOrder($event, 'quantidade_total_usuarios')">
                                                <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyTotalUsuarios }">sort</i>
                                            </a>
                                        </th-->
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
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(empresa, empresa_i) in listaFiltrada">
                                        <td><span>{{empresa.id}}</span></td>
                                        <td><span>{{empresa.cpf_cnpj}}</span></td>
                                        <td><span>{{empresa.razao_social}}</span></td>
                                        <td><span>{{empresa.quantidade_licencas}} licenças</span></td>
                                        <td>
                                            <p><span style="font-weight: bold">Usuários ativos: </span> {{empresa.quantidade_usuarios_ativos}}</p>
                                            <p><span style="font-weight: bold">Total de usuários: </span> {{empresa.quantidade_total_usuarios}}</p>
                                        </td>
                                        <!--td>{{empresa.quantidade_usuarios_ativos}}</td>
                                        <td>{{empresa.quantidade_total_usuarios}}</td-->
                                        <td>
                                            <span data-badge-caption=""
                                                  class="new badge"
                                                  :class="{'red' : !empresa.ativo, 'green' : empresa.ativo}">{{!empresa.ativo ? 'Excluído' : 'Ativo'}}</span>
                                        </td>
                                        <td><span>{{empresa.data_cadastro}}</span></td>
                                        <td>
                                            <div v-if="empresa.ativo">
                                                <a style="cursor: pointer;"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Alterar"
                                                   @click="editaEmpresa(empresa)">
                                                    <i class="small material-icons grey-text text-darken-3">edit</i>
                                                </a>
                                                &nbsp;
                                                <a style="cursor: pointer;"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Excluir"
                                                   @click="deletaEmpresa(empresa)">
                                                    <i class="small material-icons red-text">delete</i>
                                                </a>
                                            </div>
                                            <a v-else
                                               style="cursor: pointer;"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="Reativar"
                                               @click="reativaEmpresa(empresa)">
                                                <i class="small material-icons blue-text text-darken-2">restore</i>
                                            </a>
                                            <!--a class="btn-flat tooltipped"
                                               :class="{'red-text' : empresa.ativo == 0, 'green-text text-darken-2' : empresa.ativo == 1}"
                                               data-position="left"
                                               data-delay="50"
                                               data-tooltip="Clique para ativar ou inativar a empresa cadastrada."
                                               @click="alteraStatusEmpresa(empresa)">
                                                <i class="material-icons left">{{empresa.ativo == 0 ? 'thumb_down' : 'thumb_up'}}</i>{{empresa.ativo == 0 ? 'Inativo' : 'Ativo'}}
                                            </a-->
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
                    </section>

                </div>
            </div>
        </div>

        <vue-cadastra-edita-empresas ref="cadastra_edita_empresa" @emissaoDeDadosSalvos="listaEmpresasCadastradas"></vue-cadastra-edita-empresas>
    </div>
</template>

<script>

    export default {
        name: "lista_empresas",
        props: {
            usuarioLogadoJson: {
                type: String,
                default: null,
            },
            empresasCadastradasJson: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                usuarioLogado: this.usuarioLogadoJson != null && this.usuarioLogadoJson != undefined && this.usuarioLogadoJson != '' ? JSON.parse(this.usuarioLogadoJson) : null,
                empresasCadastradas: this.empresasCadastradasJson != null && this.empresasCadastradasJson != undefined && this.empresasCadastradasJson != '' ? JSON.parse(this.empresasCadastradasJson) : null,

                loading: false,

                filtro: {
                    sortProperty: 'id',
                    sortDirection: 'desc',
                    filterTerm: '',
                    sortPropertyId: true,
                    sortPropertyCPFCNPJ: false,
                    sortPropertyRazaoSocial: false,
                    sortPropertyTotalLicencas: false,
                    sortPropertyUsuariosAtivos: false,
                    sortPropertyTotalUsuarios: false,
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

                self.filtro.listaOrdenada = _.orderBy(self.empresasCadastradas.filter(function (empresa) {
                    var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                    return (
                        searchRegex.test(empresa.id) ||
                        searchRegex.test(empresa.cpf_cnpj) ||
                        searchRegex.test(empresa.razao_social) ||
                        searchRegex.test(empresa.quantidade_licencas) ||
                        searchRegex.test(empresa.quantidade_usuarios_ativos) ||
                        searchRegex.test(empresa.quantidade_total_usuarios) ||
                        searchRegex.test(empresa.ativo ? 'Ativo' : 'Inativo') ||
                        searchRegex.test(empresa.data_cadastro)
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
        },
        methods: {
            listaEmpresasCadastradas() {
                //this.$refs.alert.limpaMensagens()
                //this.loading = true

                axios.get('/empresas/lista/completa')
                    .then((response) => {
                        this.empresasCadastradas = response.data;
                        this.loading = false;
                    }).catch(error => {
                        this.$refs.alert.abreMensagens(error.response);
                        this.loading = false;
                    })
            },
            cadastraNovaEmpresa(){
                this.$refs.cadastra_edita_empresa.cadastraNovaEmpresa()
            },
            editaEmpresa(empresaParaEdicao){
                this.$refs.cadastra_edita_empresa.editaEmpresa(empresaParaEdicao)
            },
            deletaEmpresa(empresaParaExclusao){
                if(confirm('Tem certeza que deseja excluir essa empresa? Todos os cadastros (departamentos, cargos, equipes, funcionários, filtros dessa empresa serão excluídos também.')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.delete('/empresas/' + empresaParaExclusao.id).then(response => {
                        this.loading = false
                        this.listaEmpresasCadastradas()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            reativaEmpresa(empresaParaReativar){
                if(confirm('Tem certeza que deseja reativar essa empresa?')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.get('/empresas/reativa/' + empresaParaReativar.id).then(response => {
                        this.loading = false
                        this.listaEmpresasCadastradas()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            /*
            alteraStatusEmpresa(empresa){
                if(confirm('Tem certeza que deseja alterar a situação da empresa ' + (empresa.razao_social) + ' para ' + (empresa.ativo == 0 ? 'Ativo?' : 'Inativo?'))){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.put('/empresas/altera/status/' + empresa.id).then(response => {
                        this.listaEmpresasCadastrados()
                    }).catch(error => {
                        this.$refs.alert.abreMensagens(error.response)
                        this.loading = false
                    })
                }
            },
            */

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
                    case 'id':
                        this.filtro.sortPropertyId = value
                        break;
                    case 'cpf_cnpj':
                        this.filtro.sortPropertyCPFCNPJ = value
                        break;
                    case 'razao_social':
                        this.filtro.sortPropertyRazaoSocial = value
                        break;
                    case 'quantidade_licencas':
                        this.filtro.sortPropertyTotalLicencas = value
                        break;
                    case 'quantidade_usuarios_ativos':
                        this.filtro.sortPropertyUsuariosAtivos = value
                        break;
                    case 'quantidade_total_usuarios':
                        this.filtro.sortPropertyTotalUsuarios = value
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
