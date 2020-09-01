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
                            <a href="!#" class="breadcrumb">Funcionarios</a>
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
                            <h4 class="col xl6 l6 m6 s12"><i class="material-icons small">person_add</i>&nbsp;Lista de funcionarios cadastrados</h4>
                            <div class="col xl6 l6 m6 s12" v-if="empresaSelecionadaId != null && empresaSelecionadaId != 0">
                                <a class="btn-floating indigo darken-2 waves-effect waves-light right"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo && empresaSelecionada.licencas_restantes > 0"
                                   title="Cadastrar novo Funcionario"
                                   @click="cadastraNovoFuncionario"><i class="material-icons left">add</i></a>
                            </div>
                        </div>
                        <div class="row" v-if="empresaSelecionada !== undefined && empresaSelecionada != null && empresaSelecionada.licencas_restantes <= 0">
                            <div class="col s12">
                                <p class="label-sem-permissao red-text"><ul class="browser-default"><li>Suas licenças de usuário acabaram, por isso não é permitido cadastrar novos usuários, somente alterar e/ou excluir usuários já existentes.</li></ul></p>
                            </div>
                        </div>
                        <div class="row" v-else-if="empresaSelecionada !== undefined && empresaSelecionada != null && empresaSelecionada.licencas_restantes > 0">
                            <div class="col s12">
                                <h5><ul class="browser-default"><li>Você ainda tem {{empresaSelecionada.licencas_restantes}} licença(s) de usuário restante(s).</li></ul></h5>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && retornaSeTemEmpresasCadastradas && Array.isArray(empresasOuEmpresa) && empresasOuEmpresa.length > 0">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">business</i>
                                <select name="sel_empresa" id="sel_empresa"
                                        @change="selecionaEmpresaERetornaDados"
                                        v-model="empresaSelecionadaId">
                                    <option value="" disabled>Selecione uma empresa para listar os funcionarios</option>
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
                        <div class="row">
                            <div class="input-field col xl4 l4 m4 s12">
                                <i class="material-icons prefix">device_hub</i>
                                <select name="sel_departamento" id="sel_departamento"
                                        v-model="filtro.departamentoSelecionadoId">
                                    <option value=""
                                            v-if="empresaSelecionada === undefined || empresaSelecionada == null"
                                            disabled>Selecione uma empresa primeiro.</option>

                                    <option value=""
                                            v-else-if="empresaSelecionada !== undefined && empresaSelecionada != null && (empresaSelecionada.departamentos == null || empresaSelecionada.departamentos === undefined || empresaSelecionada.departamentos.length == 0)"
                                            disabled><span class="label-info-nao-encontrada">Essa empresa não possui departamentos cadastrados.</span>
                                    </option>

                                    <option v-else :value="0">Todos</option>

                                    <option v-if="empresaSelecionada !== undefined && empresaSelecionada != null && empresaSelecionada.hasOwnProperty('departamentos') && empresaSelecionada.departamentos !== undefined && empresaSelecionada.departamentos != null && empresaSelecionada.departamentos.length > 0"
                                            v-for="(departamento, departamento_i) in listaDeDepartamentosPorEmpresa"
                                            :key="'departamento_' + departamento_i"
                                            :value="departamento.id"
                                            :title="departamento.nome">
                                        <span v-if="!departamento.ativo">
                                            <strike><i>{{departamento.nome}}</i></strike>
                                            <span data-badge-caption=""
                                                  class="new badge red">Excluído</span>
                                        </span>

                                        <span v-else>{{departamento.nome}}</span>
                                    </option>
                                </select>
                                <label>Filtro por departamento</label>
                            </div>

                            <div class="input-field col xl4 l4 m4 s12">
                                <i class="fas fa-user-tag prefix"></i>
                                <select name="sel_cargo" id="sel_cargo"
                                        v-model="filtro.cargoSelecionadoId">
                                    <option value=""
                                            v-if="empresaSelecionada == null || empresaSelecionada == undefined"
                                            disabled>Selecione uma empresa primeiro.</option>

                                    <option value=""
                                            v-else-if="empresaSelecionada != null && (empresaSelecionada.departamentos === undefined || empresaSelecionada.departamentos == null || empresaSelecionada.departamentos.length == 0)"
                                            disabled><span class="label-info-nao-encontrada">Essa empresa não possui departamentos e nem cargos cadastrados.</span>
                                    </option>

                                    <option value=""
                                            v-else-if="empresaSelecionada != null && (empresaSelecionada.departamentos !== undefined && empresaSelecionada.departamentos != null && this.filtro.departamentoSelecionadoId == null)"
                                            disabled><span class="label-info-nao-encontrada">Selecione um departamento primeiro.</span>
                                    </option>

                                    <option value=""
                                            v-else-if="listaDeCargosPorDepartamento === undefined || listaDeCargosPorDepartamento == null || listaDeCargosPorDepartamento.length == 0"
                                            disabled>Esse departamento não possui cargos cadastrados</option>

                                    <option v-else :value="0">Todos</option>

                                    <option v-if="listaDeCargosPorDepartamento === undefined || listaDeCargosPorDepartamento == null || listaDeCargosPorDepartamento.length > 0"
                                            v-for="(cargo, cargo_i) in listaDeCargosPorDepartamento"
                                            :key="'cargo_' + cargo_i"
                                            :value="cargo.id"
                                            :title="cargo.nome">
                                        <span v-if="!cargo.ativo">
                                            <strike><i>{{cargo.nome}}</i></strike>
                                            <span data-badge-caption=""
                                                  class="new badge red">Excluído</span>
                                        </span>

                                        <span v-else>{{cargo.nome}}</span>
                                    </option>
                                </select>
                                <label>Filtro por cargo</label>
                            </div>

                            <div class="input-field col xl4 l4 m4 s12">
                                <i class="fas fa-people-carry prefix"></i>
                                <select name="sel_equipe" id="sel_equipe"
                                        v-model="filtro.equipeSelecionadoId">
                                    <option value=""
                                            v-if="empresaSelecionada == null || empresaSelecionada == undefined"
                                            disabled>Selecione uma empresa primeiro.</option>

                                    <option value=""
                                            v-else-if="empresaSelecionada != null && (empresaSelecionada.departamentos === undefined || empresaSelecionada.departamentos == null || empresaSelecionada.departamentos.length == 0)"
                                            disabled><span class="label-info-nao-encontrada">Essa empresa não possui departamentos e nem equipes cadastradas.</span>
                                    </option>

                                    <option value=""
                                            v-else-if="empresaSelecionada != null && (empresaSelecionada.departamentos !== undefined && empresaSelecionada.departamentos != null && this.filtro.departamentoSelecionadoId == null)"
                                            disabled><span class="label-info-nao-encontrada">Selecione um departamento primeiro.</span>
                                    </option>

                                    <option value=""
                                            v-else-if="listaDeEquipesPorDepartamento === undefined || listaDeEquipesPorDepartamento == null || listaDeEquipesPorDepartamento.length == 0"
                                            disabled>Esse departamento não possui equipes cadastradas</option>

                                    <option v-else :value="0">Todos</option>

                                    <option v-if="listaDeEquipesPorDepartamento === undefined || listaDeEquipesPorDepartamento == null || listaDeEquipesPorDepartamento.length > 0"
                                            v-for="(equipe, equipe_i) in listaDeEquipesPorDepartamento"
                                            :key="'equipe_' + equipe_i"
                                            :value="equipe.id"
                                            :title="equipe.nome">
                                        <span v-if="!equipe.ativo">
                                            <strike><i>{{equipe.nome}}</i></strike>
                                            <span data-badge-caption=""
                                                  class="new badge red">Excluído</span>
                                        </span>

                                        <span v-else>{{equipe.nome}}</span>
                                    </option>
                                </select>
                                <label>Filtro por equipe</label>
                            </div>
                        </div>

                        <div class="row" v-if="usuarioLogado.perfil.super_user == 1 && !retornaSeTemEmpresasCadastradas">
                            <div class="col s12">
                                <md-empty-state
                                    md-rounded
                                    class="red"
                                    md-icon="error"
                                    md-label="Desculpe! Não é possível continuar a operação"
                                    md-description="Para poder cadastrar os funcionarios é necessário primeiro cadastrar uma empresa.">
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
                                    md-description="Infelizmente você não pode cadastrar nenhum funcionario já que seu usuário não está associado a nenhuma empresa. Solicite ao seu administrador que o associe a alguma empresa.">
                                </md-empty-state>
                            </div>
                        </div>

                        <div v-else>
                            <md-empty-state
                                v-if="!loadingsAtivos && empresaSelecionada != null && empresaSelecionada != undefined && funcionariosCadastrados.length == 0 && empresaSelecionada.licencas_restantes > 0"
                                md-icon="person_add"
                                :md-label="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? 'Não há funcionarios cadastrados e nem é possível cadastrar novos funcionarios pois a empresa encontra-se inativa.' : 'Não há funcionarios cadastrados até o momento'"
                                :md-description="empresaSelecionada == null || empresaSelecionada == undefined || !empresaSelecionada.ativo ? '' : 'Cadastre uma novo funcionario para que possamos dar prosseguimento aos próximos cadastros.'">
                                <a class="btn indigo darken-2 waves-effect waves-light"
                                   v-if="empresaSelecionada != null && empresaSelecionada != undefined && empresaSelecionada.ativo"
                                   title="Cadastrar novo Funcionario" @click="cadastraNovoFuncionario">
                                    <i class="material-icons left">add</i>Cadastrar novo funcionario
                                </a>
                            </md-empty-state>

                            <p v-else-if="loadingTextualBuscaFuncionarios" class="label-carregamento">Carregando funcionarios cadastrados...</p>

                            <div v-else-if="funcionariosCadastrados.length > 0">
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
                                                    <span>CPF</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'cpf')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyCPF }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Login</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'usuario.login_username')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyLogin }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Funcionario</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'nome')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyFuncionario }">sort</i>
                                                    </a>
                                                </th>
                                                <th>
                                                    <span>Perfil</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'usuario.perfil.nome')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyPerfil }">sort</i>
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
                                            <tr v-for="(funcionario, funcionario_i) in listaFiltrada">
                                                <td v-if="usuarioLogado.super_user">
                                                    <span v-if="!funcionario.empresa.ativo">
                                                        <strike><i>{{funcionario.empresa.razao_social}}</i></strike>
                                                        &nbsp;&nbsp;
                                                        <span data-badge-caption=""
                                                              class="new badge red">Excluído</span>
                                                    </span>
                                                    <span v-else>{{funcionario.empresa.razao_social}}</span>
                                                </td>
                                                <td><span>{{funcionario.cpf_formatado}}</span></td>
                                                <td><span>{{funcionario.usuario.login_username}}</span></td>
                                                <td><span>{{funcionario.nome}}</span></td>
                                                <td><span>{{funcionario.usuario.perfil != null ? funcionario.usuario.perfil.nome : 'N/a'}}</span></td>
                                                <td>
                                                    <span data-badge-caption=""
                                                          class="new badge"
                                                          :class="{'red' : funcionario.ativo == 0, 'green' : funcionario.ativo == 1}">{{funcionario.ativo == 0 ? 'Excluído' : 'Ativo'}}</span>
                                                </td>
                                                <td><span>{{funcionario.data_cadastro}}</span></td>
                                                <td v-if="empresaSelecionada != null && empresaSelecionada.ativo">
                                                    <div v-if="funcionario.ativo">
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Alterar"
                                                           @click="editaFuncionario(funcionario)">
                                                            <i class="small material-icons grey-text text-darken-3">edit</i>
                                                        </a>
                                                        &nbsp;
                                                        <a style="cursor: pointer;"
                                                           data-toggle="tooltip"
                                                           data-placement="top"
                                                           title="Excluir"
                                                           @click="deletaFuncionario(funcionario)">
                                                            <i class="small material-icons red-text">delete</i>
                                                        </a>
                                                    </div>
                                                    <a v-else-if="!funcionario.ativo && funcionario.empresa.licencas_restantes > 0"
                                                       style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Reativar"
                                                       @click="reativaFuncionario(funcionario)">
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

        <vue-cadastra-edita-funcionarios ref="cadastra_edita_funcionario"
                                         @emissaoDeDadosSalvos="concluiSalvamentoDeDados"></vue-cadastra-edita-funcionarios>
    </div>
</template>

<script>

    export default {
        name: "lista_funcionarios",
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
                funcionariosCadastrados: [],
                empresaSelecionadaId: null,
                empresaSelecionada: null,

                loading: false,
                loadingTextualBuscaFuncionarios: false,
                loadingTextualBuscaDepartamentos: false,

                filtro: {
                    sortProperty: 'empresa.razao_social',
                    sortDirection: 'desc',
                    filterTerm: '',
                    departamentoSelecionadoId: null,
                    cargoSelecionadoId: null,
                    equipeSelecionadoId: null,
                    sortPropertyEmpresa: true,
                    sortPropertyCPF: false,
                    sortPropertyLogin: false,
                    sortPropertyFuncionario: false,
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

                self.filtro.listaOrdenada = _.orderBy(self.funcionariosCadastrados.filter(function (funcionario) {
                    var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                    return (
                        searchRegex.test(funcionario.empresa.razao_social) ||
                        searchRegex.test(funcionario.cpf) ||
                        searchRegex.test(funcionario.usuario.login_username) ||
                        searchRegex.test(funcionario.nome) ||
                        searchRegex.test(funcionario.usuario.perfil != null ? funcionario.usuario.perfil.nome : 'N/a') ||
                        searchRegex.test(funcionario.ativo == 1 ? 'Ativo' : 'Excluído') ||
                        searchRegex.test(funcionario.data_cadastro)
                    )
                }), this.filtro.sortProperty, this.filtro.sortDirection)

                if(this.filtro.departamentoSelecionadoId != null && this.filtro.departamentoSelecionadoId != undefined && this.filtro.departamentoSelecionadoId != 0) {
                    if (self.filtro.listaOrdenada.length == 0)
                        self.filtro.listaOrdenada = _.filter(self.funcionariosCadastrados, (funcionario) => { return (funcionario.departamento != null && funcionario.departamento.id == this.filtro.departamentoSelecionadoId) })
                    else
                        self.filtro.listaOrdenada = _.filter(self.filtro.listaOrdenada, (funcionario) => { return (funcionario.departamento != null && funcionario.departamento.id == this.filtro.departamentoSelecionadoId) })
                }

                if(this.filtro.cargoSelecionadoId != null && this.filtro.cargoSelecionadoId != undefined && this.filtro.cargoSelecionadoId != 0){
                    if(self.filtro.listaOrdenada.length == 0)
                        self.filtro.listaOrdenada = _.filter(self.funcionariosCadastrados, (funcionario) => { return (funcionario.cargo != null && funcionario.cargo.id == this.filtro.cargoSelecionadoId) })
                    else
                        self.filtro.listaOrdenada = _.filter(self.filtro.listaOrdenada, (funcionario) => { return (funcionario.cargo != null && funcionario.cargo.id == this.filtro.cargoSelecionadoId) })
                }

                if(this.filtro.equipeSelecionadoId != null && this.filtro.equipeSelecionadoId != undefined && this.filtro.equipeSelecionadoId != 0) {
                    if (self.filtro.listaOrdenada.length == 0)
                        self.filtro.listaOrdenada = _.filter(self.funcionariosCadastrados, (funcionario) => { return (funcionario.equipe != null && funcionario.equipe.id == this.filtro.equipeSelecionadoId) })
                    else
                        self.filtro.listaOrdenada = _.filter(self.filtro.listaOrdenada, (funcionario) => { return (funcionario.equipe != null && funcionario.equipe.id == this.filtro.equipeSelecionadoId) })
                }

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
                return (this.loadingTextualBuscaFuncionarios || this.loading)
            },
            listaDeDepartamentosPorEmpresa(){
                if(this.empresaSelecionada === undefined || this.empresaSelecionada == null){
                    this.filtro.departamentoSelecionadoId = 0

                    return []
                }
                else{
                    this.filtro.departamentoSelecionadoId = 0

                    return this.empresaSelecionada.departamentos
                }
            },
            listaDeCargosPorDepartamento(){
                if(this.empresaSelecionada == null ||
                    this.empresaSelecionada === undefined ||
                    this.empresaSelecionada.departamentos == null ||
                    this.empresaSelecionada.departamentos === undefined ||
                    this.empresaSelecionada.departamentos.length == 0 ||
                    this.filtro.departamentoSelecionadoId == null) {

                    this.filtro.cargoSelecionadoId = 0

                    return []
                }
                else {
                    let departamento_selecionado = _.find(this.empresaSelecionada.departamentos, (departamento) => {
                        return departamento.id == this.filtro.departamentoSelecionadoId
                    })

                    this.filtro.cargoSelecionadoId = 0

                    if(departamento_selecionado === undefined || departamento_selecionado == null)
                        return []

                    return departamento_selecionado.cargos
                }
            },
            listaDeEquipesPorDepartamento(){
                if(this.empresaSelecionada == null ||
                    this.empresaSelecionada === undefined ||
                    this.empresaSelecionada.departamentos == null ||
                    this.empresaSelecionada.departamentos === undefined ||
                    this.empresaSelecionada.departamentos.length == 0 ||
                    this.filtro.departamentoSelecionadoId == null) {

                    this.filtro.equipeSelecionadoId = 0

                    return []
                }
                else{
                    let departamento_selecionado = _.find(this.empresaSelecionada.departamentos, (departamento) => {
                        return departamento.id == this.filtro.departamentoSelecionadoId
                    })

                    this.filtro.equipeSelecionadoId = 0

                    if(departamento_selecionado === undefined || departamento_selecionado == null)
                        return []

                    return departamento_selecionado.equipes
                }
            },
        },
        watch: {
            empresaSelecionadaId: function (newValue) {
                this.preencheDadosEmpresaSelecionada(newValue)
            }
        },
        updated() {
            $('#sel_empresa').formSelect()
            $('#sel_departamento').formSelect()
            $('#sel_cargo').formSelect()
            $('#sel_equipe').formSelect()
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
                        this.selecionaEmpresaERetornaDados()
                    }
            },

            selecionaEmpresaERetornaDados(){
                this.listaFuncionariosCadastrados()
                //this.listaDepartamentosCadastrados()
            },

            listaFuncionariosCadastrados(atualizaEmpresa = false) {
                this.$refs.alert.limpaMensagens()

                if(this.empresaSelecionadaId == null || this.empresaSelecionadaId == 0){
                    this.$refs.alert.abreMensagens({status_resposta: 'error', msg: 'É obrigatório selecionar uma empresa válida para listar os funcionarios cadastrados.'})
                    return
                }

                let request = {empresaId: this.empresaSelecionadaId, relacionamentos: ['departamento', 'equipe', 'cargo', 'usuario.perfil', 'empresa.departamentos.cargos', 'empresa.departamentos.equipes', 'empresa.perfis']}

                this.loadingTextualBuscaFuncionarios = true

                axios.post('funcionarios/lista/por/empresa/json', request)
                    .then((response) => {
                        this.funcionariosCadastrados = response.data;
                        this.loadingTextualBuscaFuncionarios = false;

                        if(atualizaEmpresa)
                            this.atualizaDadosEmpresaSelecionada()
                    }).catch(error => {
                        if(this.usuarioLogado.super_user)
                            this.empresaSelecionadaId = null;

                        this.$refs.alert.abreMensagens(error.response);
                        this.loadingTextualBuscaFuncionarios = false;
                    })
            },
            cadastraNovoFuncionario(){
                if(this.empresaSelecionada == null || this.empresaSelecionada === 'undefined')
                    Swal.fire('Oops!', 'Não é possível cadastrar um funcionario sem antes selecionar uma empresa.','error')
                else{
                    this.$refs.cadastra_edita_funcionario.cadastraNovoFuncionario(this.empresaSelecionada)
                }
            },
            editaFuncionario(funcionarioParaEdicao){
                this.$refs.cadastra_edita_funcionario.editaFuncionario(funcionarioParaEdicao)
            },
            retornaSeTemEmpresasCadastradas(){
                return ((!Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa != null) || (Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa.length != 0))
            },
            deletaFuncionario(funcionarioParaExclusao){
                if(confirm('Tem certeza que deseja excluir esse funcionario? As configurações de filtro específica de usuário e a carteira de clientes do usuário será deletada automaticamente.')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.delete('/funcionarios/' + funcionarioParaExclusao.id).then(response => {
                        this.loading = false
                        this.concluiSalvamentoDeDados()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            reativaFuncionario(funcionarioParaReativar){
                if(confirm('Tem certeza que deseja reativar esse funcionario? Não serão reativados os demais cadastros desse funcionário (como filtros e carteira de clientes), se fazendo necessário reativar ou recadastrar um por um.')){
                    this.$refs.alert.limpaMensagens()
                    this.loading = true

                    axios.get('/funcionarios/reativa/' + funcionarioParaReativar.id).then(response => {
                        this.loading = false
                        this.concluiSalvamentoDeDados()
                        this.$refs.alert.abreMensagens(response)
                        Swal.fire('Ótimo', response.data.msg, 'success')
                    }).catch(error => {
                        this.loading = false
                        this.$refs.alert.abreMensagens(error.response)
                        Swal.fire('Desculpe! Ocorreu um erro.', error.response.data.msg, 'error')
                    })
                }
            },
            preencheDadosEmpresaSelecionada(newValue){
                if(newValue == null || newValue === 0)
                    this.empresaSelecionada = null
                else
                if(Array.isArray(this.empresasOuEmpresa))
                    this.empresaSelecionada = _.find(this.empresasOuEmpresa, (empresa) =>  { return empresa.id === newValue })
                else
                    this.empresaSelecionada = this.empresasOuEmpresa
            },
            concluiSalvamentoDeDados(){
                this.listaFuncionariosCadastrados(true)
            },
            atualizaDadosEmpresaSelecionada(){
                let funcionario = this.funcionariosCadastrados[0]

                if(Array.isArray(this.empresasOuEmpresa) && this.empresasOuEmpresa.length > 0){
                    let indice = _.findIndex(this.empresasOuEmpresa, (empresa) => { return empresa.id == funcionario.empresa.id })
                    this.empresasOuEmpresa[indice] = funcionario.empresa
                }else
                    this.empresasOuEmpresa = funcionario.empresa

                this.preencheDadosEmpresaSelecionada(funcionario.empresa.id)
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
                    case 'cpf':
                        this.filtro.sortPropertyCPF = value
                        break;
                    case 'usuario.login_username':
                        this.filtro.sortPropertyLogin = value
                        break;
                    case 'nome':
                        this.filtro.sortPropertyFuncionario = value
                        break;
                    case 'usuario.perfil.nome':
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
