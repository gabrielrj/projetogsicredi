<template>
    <div class="modal-cadastro">
        <div id="modalCadastroFuncionario" class="modal">
            <vue-loading :loading="form.loading" :is-full-page="true"></vue-loading>

            <div class="modal-content">
                <h4>{{form.funcionario.id == 0 || form.funcionario.id == null ? 'Cadastrar novo funcionario' : 'Alterar funcionario'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div>
                        <section id="secao_dados_empresa">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">business</i>
                                    <input type="text" name="empresa" id="empresa"
                                           disabled
                                           :value="form.funcionario.hasOwnProperty('empresa') && form.funcionario.empresa != null && form.funcionario.empresa.hasOwnProperty('razao_social') ? form.funcionario.empresa.razao_social : null">
                                    <label for="empresa">Empresa</label>
                                </div>
                            </div>
                        </section>

                        <section id="secao_dados_funcionario">
                            <div class="row">
                                <div class="input-field col xl3 l3 m12 s12">
                                    <!--CPF-->
                                    <i class="far fa-id-card prefix"></i>
                                    <the-mask name="cpf"
                                              id="cpf"
                                              ref="cpf"
                                              :class="getValidationClass('cpf')"
                                              @change.native="resetValidationProp('cpf')"
                                              :masked="false"
                                              v-model="form.funcionario.cpf"
                                              :mask="['###.###.###-##']"
                                              :disabled="form.loading"></the-mask>
                                    <label for="cpf">CPF</label>
                                    <span class="helper-text" data-error="Campo obrigatório!"
                                          v-if="!$v.form.funcionario.cpf.required"></span>
                                    <span class="helper-text" data-error="Inválido! O CPF tem que ter no mínimo 14 caracteres."
                                          v-else-if="!$v.form.funcionario.cpf.minlength"></span>
                                    <span class="helper-text" data-error="Inválido! O CPF tem que ter no máximo 14 caracteres."
                                          v-else-if="!$v.form.funcionario.cpf.maxlength"></span>
                                </div>
                                <div class="input-field col xl9 l9 m12 s12">
                                    <!--NOME-->
                                    <i class="material-icons prefix">account_circle</i>
                                    <input name="nome"
                                           id="nome"
                                           type="text"
                                           :class="getValidationClass('nome')"
                                           @change="resetValidationProp('nome')"
                                           maxlength="100"
                                           v-model="form.funcionario.nome"
                                           :disabled="form.loading"
                                           oninput="this.value = this.value.toUpperCase()"/>
                                    <label for="nome">Nome</label>
                                    <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.funcionario.nome.required"></span>
                                    <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.funcionario.nome.minlength"></span>
                                    <span class="helper-text" data-error="Máximo de 100 caracteres!" v-else-if="!$v.form.funcionario.nome.maxlength"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col xl6 l6 m6 s12">
                                    <!--RG-->
                                    <i class="fas fa-fingerprint prefix"></i>
                                    <input name="rg"
                                           id="rg"
                                           type="text"
                                           :class="getValidationClass('rg')"
                                           @change="resetValidationProp('rg')"
                                           maxlength="100"
                                           v-model="form.funcionario.rg"
                                           :disabled="form.loading"
                                           placeholder="Opcional"/>
                                    <label for="rg">RG</label>
                                    <span class="helper-text" data-error="Máximo de 20 caracteres!" v-if="!$v.form.funcionario.rg.maxlength"></span>
                                </div>
                                <div class="input-field col xl6 l6 m6 s12">
                                    <!--Matrícula-->
                                    <i class="fas fa-id-card-alt prefix"></i>
                                    <input name="matricula"
                                           id="matricula"
                                           type="text"
                                           :class="getValidationClass('matricula')"
                                           @change="resetValidationProp('matricula')"
                                           maxlength="150"
                                           v-model="form.funcionario.matricula"
                                           :disabled="form.loading"
                                           placeholder="Opcional"/>
                                    <label for="matricula">Matrícula</label>
                                    <span class="helper-text" data-error="Máximo de 150 caracteres!" v-if="!$v.form.funcionario.matricula.maxlength"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col xl6 l6 m6 s12">
                                    <!--Telefone Fixo-->
                                    <i class="material-icons prefix">phone</i>
                                    <the-mask name="telefone"
                                              id="telefone"
                                              v-model="form.funcionario.telefone_fixo"
                                              :disabled="form.loading"
                                              maxlength="14"
                                              placeholder="Opcional"
                                              :masked="false"
                                              :mask="['(##) ####-####']">
                                    </the-mask>
                                    <label for="telefone">Telefone</label>
                                </div>
                                <div class="input-field col xl6 l6 m6 s12">
                                    <!--Telefone Celular-->
                                    <i class="material-icons prefix">smartphone</i>
                                    <the-mask name="celular"
                                              id="celular"
                                              v-model="form.funcionario.telefone_celular"
                                              :disabled="form.loading"
                                              maxlength="15"
                                              placeholder="Opcional"
                                              :masked="false"
                                              :mask="['(##) 9####-####']">
                                    </the-mask>
                                    <label for="celular">Celular</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <!--E-mail-->
                                    <i class="material-icons prefix">email</i>
                                    <input name="email"
                                           id="email"
                                           type="text"
                                           :class="getValidationClass('usuario.email')"
                                           @change="resetValidationProp('usuario.email')"
                                           maxlength="150"
                                           v-model="form.funcionario.usuario.email"
                                           :disabled="form.loading"
                                           placeholder="Opcional"/>
                                    <label for="email">E-mail</label>
                                    <span class="helper-text" data-error="Máximo de 150 caracteres!" v-if="!$v.form.funcionario.usuario.email.maxlength"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col xl4 l4 m4 s12">
                                    <!--Departamento-->
                                    <i class="material-icons prefix">device_hub</i>
                                    <select name="sel_departamento_funcionario" id="sel_departamento_funcionario"
                                            :disabled="form.loading"
                                            v-model="form.funcionario.departamentos_id">
                                        <option value="" disabled>Selecione um departamento.</option>
                                        <option v-for="(departamento, departamento_i) in listaDepartamentosAtivosDaEmpresa"
                                                :key="'opt_departamento_' + departamento_i"
                                                :title="departamento.nome"
                                                :value="departamento.id">{{departamento.nome}}</option>
                                    </select>
                                    <label>Departamento</label>
                                </div>
                                <div class="input-field col xl4 l4 m4 s12">
                                    <!--Equipe-->
                                    <i class="fas fa-people-carry prefix"></i>
                                    <select name="sel_equipe_funcionario" id="sel_equipe_funcionario"
                                            :disabled="form.loading"
                                            v-model="form.funcionario.equipes_id">
                                        <option value="" disabled>Selecione uma equipe.</option>
                                        <option v-for="(equipe, equipe_i) in listaDeEquipesAtivasPorDepartamento"
                                                :key="'opt_equipe_' + equipe_i"
                                                :title="equipe.nome"
                                                :value="equipe.id">{{equipe.nome}}</option>
                                    </select>
                                    <label>Equipe</label>
                                </div>
                                <div class="input-field col xl4 l4 m4 s12">
                                    <!--Cargo-->
                                    <i class="fas fa-user-tag prefix"></i>
                                    <select name="sel_cargo_funcionario" id="sel_cargo_funcionario"
                                            :disabled="form.loading"
                                            v-model="form.funcionario.cargos_id">
                                        <option value="" disabled>Selecione um cargo.</option>
                                        <option v-for="(cargo, cargo_i) in listaDeCargosAtivosPorDepartamento"
                                                :key="'opt_cargo_' + cargo_i"
                                                :title="cargo.nome"
                                                :value="cargo.id">{{cargo.nome}}</option>
                                    </select>
                                    <label>Cargo</label>
                                </div>
                            </div>
                        </section>

                        <section id="secao_dados_usuario">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card-panel">
                                        <h5>Dados do usuário</h5>
                                        <div class="row">
                                            <div class="input-field col xl6 l6 m6 s12">
                                                <!--Perfil-->
                                                <i class="fas fa-id-card prefix"></i>
                                                <select name="sel_perfil_usuario" id="sel_perfil_usuario"
                                                        @change="resetValidationProp('pefil_usuario', true)"
                                                        :disabled="form.loading"
                                                        v-model="form.funcionario.usuario.perfil_id">
                                                    <option value="" disabled>Selecione um perfil.</option>
                                                    <option v-for="(perfil, perfil_i) in listaPerfisAtivosDaEmpresa"
                                                            :key="'opt_perfil_' + perfil_i"
                                                            :title="perfil.nome"
                                                            :value="perfil.id">{{perfil.nome}}</option>
                                                </select>
                                                <label>Perfil</label>
                                                <span class="helper-text" data-error="Campo obrigatório!" v-if="form.funcionario.usuario.perfil_id == null || form.funcionario.usuario.perfil_id === 0"></span>
                                            </div>
                                            <div class="col xl6 l6 m6 s12">
                                                <!--Ativo-->
                                                <div class="switch" style="display: inline;">
                                                    <label>
                                                        Inativo
                                                        <input type="checkbox"
                                                               :disabled="form.funcionario.id === undefined || form.funcionario.id == null || form.funcionario.id == 0"
                                                               v-model="form.funcionario.usuario.active">
                                                        <span class="lever"></span>
                                                        Ativo
                                                    </label>
                                                </div>
                                                <br>
                                                <!--Pendente trocar a senha-->
                                                <p>
                                                    <label>
                                                        <input type="checkbox" class="filled-in"
                                                               :disabled="form.funcionario.id === undefined || form.funcionario.id == null || form.funcionario.id == 0"
                                                               v-model="form.funcionario.usuario.pendente_trocar_senha" />
                                                        <span>Pendente trocar a senha</span>
                                                    </label>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <i class="material-icons prefix">business</i>
                                                <input type="text" name="codigo_empresa" id="codigo_empresa"
                                                       style="color: red"
                                                       disabled
                                                       :value="form.funcionario.hasOwnProperty('empresa') && form.funcionario.empresa != null && form.funcionario.empresa.hasOwnProperty('id') ? form.funcionario.empresa.id : null">
                                                <label for="codigo_empresa">Código da Empresa</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col xl6 l6 m6 s12">
                                                <!--Login-->
                                                <i class="material-icons prefix">account_box</i>
                                                <input name="login_username"
                                                       id="login_username"
                                                       type="text"
                                                       :class="getValidationClass('usuario.login_username')"
                                                       @change="resetValidationProp('usuario.login_username')"
                                                       maxlength="250"
                                                       v-model="form.funcionario.usuario.login_username"
                                                       :disabled="form.loading"
                                                       oninput="this.value = this.value.toUpperCase()"/>
                                                <label for="login_username">Login</label>
                                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.funcionario.usuario.login_username.required"></span>
                                                <span class="helper-text" data-error="Mínimo de 5 caracteres!" v-else-if="!$v.form.funcionario.usuario.login_username.minlength"></span>
                                                <span class="helper-text" data-error="Máximo de 250 caracteres!" v-else-if="!$v.form.funcionario.usuario.login_username.maxlength"></span>
                                            </div>
                                            <div class="input-field col xl6 l6 m6 s12">
                                                <!--Senha-->
                                                <i class="material-icons prefix">security</i>
                                                <input name="senha"
                                                       id="senha"
                                                       type="password"
                                                       :class="getValidationClass('usuario.password')"
                                                       @change="resetValidationProp('usuario.password')"
                                                       maxlength="20"
                                                       v-model="form.funcionario.usuario.password"
                                                       :disabled="form.loading"/>
                                                <label for="senha">Senha</label>
                                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.funcionario.usuario.password.required"></span>
                                                <span class="helper-text" data-error="Máximo de 20 caracteres!" v-else-if="!$v.form.funcionario.usuario.password.maxlength"></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <a class="btn red darken-1 waves-effect waves-light" @click="fechaJanela">
                    <i class="material-icons left">cancel</i>
                    CANCELAR
                </a>
                <a class="btn blue darken-4 waves-effect waves-light"
                   @click="salva"
                   :disabled="form.loading">
                    <i class="material-icons left">save</i>
                    SALVAR
                </a>
            </div>
        </div>

    </div>
</template>

<script>
    import {
        required,
        requiredIf,
        minLength,
        maxLength,
    } from 'vuelidate/lib/validators'

    export default {
        name: "cadastra_edita_funcionarios",
        data() {
            return {
                form: {
                    funcionario: {
                        id: null,
                        nome: null,
                        rg: null,
                        cpf: null,
                        telefone_fixo: null,
                        telefone_celular: null,
                        matricula: null,
                        situacao_funcionarios_id: 1,
                        departamentos_id: null,
                        cargos_id: null,
                        equipes_id: null,
                        empresa_id: null,
                        empresa: null,
                        departamento: null,
                        equipe: null,
                        cargo: null,
                        usuario: {
                            id: null,
                            name: null,
                            email: null,
                            login: null,
                            password: null,
                            active: true,
                            pendente_trocar_senha: true,
                            funcionarios_id: null,
                            perfil_id: null,
                            login_username: null,
                            perfil: null,
                        }
                    },

                    loading: false,
                },
                abreJanelaForm: false,
                janelaForm: null,
            }
        },
        validations: {
            form: {
                funcionario: {
                    nome: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(100)
                    },
                    cpf: {
                        required,
                        minLength: minLength(11),
                        maxLength: maxLength(11)
                    },
                    rg: {
                        maxLength: maxLength(20),
                    },
                    matricula: {
                        maxLength: maxLength(150),
                    },
                    usuario: {
                        email: {
                            maxLength: maxLength(150)
                        },
                        login_username: {
                            required,
                            minLength: minLength(5),
                            maxLength: maxLength(250)
                        },
                        password: {
                            required: requiredIf((usuario) => {
                                return (usuario.id == null || usuario.id == 0)
                            }),
                            maxLength: maxLength(20)
                        }
                    }
                }
            }
        },
        computed: {
            listaPerfisAtivosDaEmpresa(){
                if(this.form.funcionario.empresa === undefined || this.form.funcionario.empresa == null || this.form.funcionario.empresa.perfis === undefined || this.form.funcionario.empresa.perfis == null || this.form.funcionario.empresa.perfis.length == 0)
                    return []
                else
                    return _.filter(this.form.funcionario.empresa.perfis, (perfil) => {return perfil.ativo}) //Retorna perfis ativos

            },
            listaDepartamentosAtivosDaEmpresa(){
                if(this.form.funcionario.empresa === undefined || this.form.funcionario.empresa == null || this.form.funcionario.empresa.departamentos === undefined || this.form.funcionario.empresa.departamentos == null)
                    return []
                else
                    return _.filter(this.form.funcionario.empresa.departamentos, (departamento) => {return departamento.ativo}) //Retorna departamentos ativos

            },
            listaDeCargosAtivosPorDepartamento(){
                if(this.form.funcionario.empresa !== undefined &&
                    this.form.funcionario.empresa != null &&
                    this.form.funcionario.empresa.departamentos !== undefined &&
                    this.form.funcionario.empresa.departamentos != null &&
                    this.form.funcionario.departamentos_id != null) {

                    let departamento_selecionado = _.find(this.form.funcionario.empresa.departamentos, (departamento) => {
                        return departamento.id == this.form.funcionario.departamentos_id
                    })

                    if(departamento_selecionado === undefined || departamento_selecionado == null)
                        return []

                    return _.filter(departamento_selecionado.cargos, (cargo) => { return cargo.ativo}) //retorna somente os cargos ativos do departamento
                }
                else{
                    this.form.funcionario.cargos_id = null

                    return []
                }
            },
            listaDeEquipesAtivasPorDepartamento(){
                if(this.form.funcionario.empresa !== undefined &&
                    this.form.funcionario.empresa != null &&
                    this.form.funcionario.empresa.departamentos !== undefined &&
                    this.form.funcionario.empresa.departamentos != null &&
                    this.form.funcionario.departamentos_id != null) {

                    let departamento_selecionado = _.find(this.form.funcionario.empresa.departamentos, (departamento) => {
                        return departamento.id == this.form.funcionario.departamentos_id
                    })

                    if(departamento_selecionado === undefined || departamento_selecionado == null)
                        return []

                    return _.filter(departamento_selecionado.equipes, (equipe) => { return equipe.ativo}) //retorna somente as equipes ativas do departamento
                }
                else{
                    this.form.funcionario.equipes_id = null

                    return []
                }
            },
        },
        watch: {
            abreJanelaForm(newValue) {
                if(newValue) {
                    this.janelaForm.open()
                }else{
                    this.janelaForm.close()
                    this.janelaForm.destroy()
                }
            }
        },
        mounted() {
            const elemento = document.getElementById('modalCadastroFuncionario')
            //const instancia = M.Modal.init(elemento, {dismissible: false})
            const instancia = M.Modal.init(elemento, {
                dismissible: false,
                onCloseEnd: () => {
                    this.eventoDeFechamentoDeJanela()
                },
                onOpenEnd: () => {
                    $(elemento).css('transform', '')
                },
            })
            this.janelaForm = instancia
        },
        updated() {
            M.updateTextFields();

            $('#sel_departamento_funcionario').formSelect()
            $('#sel_equipe_funcionario').formSelect()
            $('#sel_cargo_funcionario').formSelect()
            $('#sel_perfil_usuario').formSelect()
        },
        methods: {
            abreJanela(){
                this.abreJanelaForm = true
            },

            fechaJanela(){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()
                this.abreJanelaForm = false
            },

            eventoDeFechamentoDeJanela(){
                this.abreJanelaForm = false
            },

            limpaCamposFormulario(){
                this.$refs.cpf.lastValue = null
                this.$refs.cpf.display = null
                this.form.funcionario.id = null
                this.form.funcionario.nome = null
                this.form.funcionario.rg = null
                this.form.funcionario.cpf = null
                this.form.funcionario.telefone_fixo = null
                this.form.funcionario.telefone_celular = null
                this.form.funcionario.matricula = null
                this.form.funcionario.situacao_funcionarios_id = 1
                this.form.funcionario.departamentos_id = null
                this.form.funcionario.cargos_id = null
                this.form.funcionario.equipes_id = null
                this.form.funcionario.empresa_id = null
                this.form.funcionario.empresa = null
                this.form.funcionario.departamento = null
                this.form.funcionario.equipe = null
                this.form.funcionario.cargo = null
                this.form.funcionario.usuario.id = null
                this.form.funcionario.usuario.name = null
                this.form.funcionario.usuario.email = null
                this.form.funcionario.usuario.login = null
                this.form.funcionario.usuario.password = null
                this.form.funcionario.usuario.active = true
                this.form.funcionario.usuario.pendente_trocar_senha = true
                this.form.funcionario.usuario.funcionarios_id = null
                this.form.funcionario.usuario.perfil_id = null
                this.form.funcionario.usuario.login_username = null
                this.form.funcionario.usuario.perfil = null

                this.form.loading = false
            },

            preencheDadosFuncionario(funcionarioParaEdicao){
                this.form.funcionario.id = funcionarioParaEdicao.id
                this.form.funcionario.nome = funcionarioParaEdicao.nome
                this.form.funcionario.rg = funcionarioParaEdicao.rg
                this.form.funcionario.cpf = funcionarioParaEdicao.cpf
                this.form.funcionario.telefone_fixo = funcionarioParaEdicao.telefone_fixo
                this.form.funcionario.telefone_celular = funcionarioParaEdicao.telefone_celular
                this.form.funcionario.matricula = funcionarioParaEdicao.matricula
                this.form.funcionario.situacao_funcionarios_id = funcionarioParaEdicao.situacao_funcionarios_id
                this.form.funcionario.departamentos_id = funcionarioParaEdicao.departamentos_id
                this.form.funcionario.cargos_id = funcionarioParaEdicao.cargos_id
                this.form.funcionario.equipes_id = funcionarioParaEdicao.equipes_id
                this.form.funcionario.empresa_id = funcionarioParaEdicao.empresa_id
                this.form.funcionario.empresa = funcionarioParaEdicao.empresa
                this.form.funcionario.departamento = funcionarioParaEdicao.departamento
                this.form.funcionario.equipe = funcionarioParaEdicao.equipe
                this.form.funcionario.cargo = funcionarioParaEdicao.cargo
                this.form.funcionario.usuario.id = funcionarioParaEdicao.usuario.id
                this.form.funcionario.usuario.name = funcionarioParaEdicao.usuario.name
                this.form.funcionario.usuario.email = funcionarioParaEdicao.usuario.email
                this.form.funcionario.usuario.login = funcionarioParaEdicao.usuario.login
                this.form.funcionario.usuario.password = null
                this.form.funcionario.usuario.active = funcionarioParaEdicao.usuario.active
                this.form.funcionario.usuario.pendente_trocar_senha = funcionarioParaEdicao.usuario.pendente_trocar_senha
                this.form.funcionario.usuario.funcionarios_id = funcionarioParaEdicao.usuario.funcionarios_id
                this.form.funcionario.usuario.perfil = funcionarioParaEdicao.usuario.perfil
                this.form.funcionario.usuario.perfil_id = funcionarioParaEdicao.usuario.perfil_id
                this.form.funcionario.usuario.login_username = funcionarioParaEdicao.usuario.login_username
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoFuncionario(empresa){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.funcionario.empresa = empresa
                this.form.funcionario.empresa_id = empresa.id

                this.abreJanela()
            },

            editaFuncionario(funcionarioParaEdicao){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.preencheDadosFuncionario(funcionarioParaEdicao)

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName, isSelect = false){
                if(isSelect){
                    if (this.form.funcionario.usuario.perfil_id != null && this.form.funcionario.usuario.perfil_id !== 0)
                        $('#sel_' + fieldName).parents('div.select-wrapper').removeClass('invalid')
                }else{
                    let fieldSplit = fieldName.split('.')
                    let fieldOrigin = this.$v.form['funcionario']
                    let fieldFiltered = null
                    let contador = 0

                    _.forEach(fieldSplit, (fieldItem) => {
                        if(contador == 0)
                            fieldFiltered = fieldOrigin[fieldItem]
                        else
                            fieldFiltered = fieldFiltered[fieldItem]

                        contador++
                    })
                    //let field = null

                    const field = fieldFiltered

                    field.$reset()
                }
            },
            getValidationClass (fieldName) {
                let fieldSplit = fieldName.split('.')
                let fieldOrigin = this.$v.form['funcionario']
                let fieldFiltered = null
                let contador = 0

                _.forEach(fieldSplit, (fieldItem) => {
                    if(contador == 0)
                        fieldFiltered = fieldOrigin[fieldItem]
                    else
                        fieldFiltered = fieldFiltered[fieldItem]

                    contador++
                })
                //let field = null

                const field = fieldFiltered

                if (field) {
                    return {
                        'invalid': field.$invalid && field.$dirty
                    }
                }
            },
            valida(){
                let perfil_valido = this.form.funcionario.usuario.perfil_id != null && this.form.funcionario.usuario.perfil_id !== 0
                if(!perfil_valido)
                    $('#sel_perfil_usuario').parents('div.select-wrapper').addClass('invalid')

                this.$v.$touch()

                return (!this.$v.$invalid && perfil_valido)
            },
            // Eventos de validação do formulário

            salva(){
                if(this.valida()){
                    this.limpaMensagensDeErro()
                    this.form.loading = true

                    if(this.form.funcionario.id == null || this.form.funcionario.id == 0){
                        axios.post('/funcionarios', this.form.funcionario)
                            .then(response => {
                                this.form.loading = false
                                Swal.fire(
                                    'Ótimo!',
                                    response.data.msg,
                                    'success'
                                )
                                this.concluiSalvamentoDosDados()
                            })
                            .catch(error => {
                                $('#modalCadastroFuncionario').scrollTop(0)
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar cadastrar esse funcionario, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/funcionarios/' + this.form.funcionario.id, this.form.funcionario)
                            .then(response => {
                                this.form.loading = false
                                Swal.fire(
                                    'Ótimo!',
                                    response.data.msg,
                                    'success'
                                )
                                this.concluiSalvamentoDosDados()
                            })
                            .catch(error => {
                                $('#modalCadastroFuncionario').scrollTop(0)
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar alterar essa funcionario, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }
                }
            },

            concluiSalvamentoDosDados(){
                this.$emit('emissaoDeDadosSalvos')
                this.fechaJanela()
            }
        }

    }
</script>

<style scoped>
    div#modalCadastroFuncionario.modal{
        width: 80% !important;
        max-height: 90% !important;
        height: 90% !important;
        top: 5% !important;
    }
</style>
