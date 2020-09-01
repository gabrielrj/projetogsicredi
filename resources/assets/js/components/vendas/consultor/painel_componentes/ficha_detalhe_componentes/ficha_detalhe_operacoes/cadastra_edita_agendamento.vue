<template>
    <div class="modal-cadastro">
        <div class="modal" id="modalFormCadastroAgendamento">

            <div class="modal-content">
                <vue-loading :loading="form.loading" :is-full-page="true"></vue-loading>

                <h4>{{form.agendamento.id == 0 || form.agendamento.id == null ? 'Criar agendamento' : 'Alterar agendamento'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="usuario" name="usuario"
                                   disabled
                                   :value="form.agendamento.usuario != null ? form.agendamento.usuario.funcionario.nome : 'N/a'">
                            <label for="usuario">Consultor</label>
                        </div>
                    </div>

                    <div class="row">
                        <p>
                            Qual o tipo de agendamento?
                        </p>
                    </div>

                    <div class="row">
                        <div class="col custom-col">
                            <label>
                                <input tipo="materialize"  name="tipo_de_agendamento"
                                       class="with-gap"
                                       type="radio"
                                       id="rdTipoDeAgendamentoVisita"
                                       :disabled="form.loading"
                                       :value="1"
                                       v-model="form.agendamento.tipo_id" />
                                <span>Visita</span>
                            </label>
                        </div>
                        <div class="col custom-col">
                            <label>
                                <input tipo="materialize"  name="tipo_de_agendamento"
                                       class="with-gap"
                                       type="radio"
                                       id="rdTipoDeAgendamentoChamada"
                                       :disabled="form.loading"
                                       :value="2"
                                       v-model="form.agendamento.tipo_id" />
                                <span>Chamada</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col m6 s12">
                            <input type="text" class="datepicker"
                                   id="data_agendamento"
                                   name="data_agendamento"
                                   tipo="periodo_atual"
                                   placeholder="Data"
                                   :class="getValidationClass('data_agendamento')"
                                   @change="resetValidationProp('data_agendamento')"
                                   :disabled="form.loading"
                                   v-model.lazy="form.agendamento.data_agendamento">
                            <label for="data_agendamento">Data Agendada</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.data_agendamento.required"></span>
                        </div>

                        <div class="input-field col m6 s12">
                            <input type="text" class="timepicker"
                                   id="hora_agendamento"
                                   name="hora_agendamento"
                                   placeholder="Hora"
                                   :class="getValidationClass('hora_agendamento')"
                                   @change="resetValidationProp('hora_agendamento')"
                                   :disabled="form.loading"
                                   v-model.lazy="form.agendamento.hora_agendamento">
                            <label for="hora_agendamento">Hora Agendada</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.hora_agendamento.required"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <the-mask name="numero_telefone"
                                      id="numero_telefone"
                                      :class="getValidationClass('numero_telefone')"
                                      @change="resetValidationProp('numero_telefone')"
                                      v-model="form.agendamento.numero_telefone"
                                      :disabled="form.loading"
                                      :masked="true"
                                      :mask="['(##) ####-####', '(##) #####-####']"></the-mask>
                            <label for="numero_telefone">Telefone</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.numero_telefone.required"></span>
                            <span class="helper-text" data-error="Telefone inválido!" v-else-if="!$v.form.agendamento.numero_telefone.minlength"></span>
                        </div>
                    </div>

                    <section v-if="form.agendamento.tipo_id == TipoAgendamento.VISITA">
                        <div class="row">
                            <div class="input-field col m3 s12">
                                <the-mask name="cep"
                                          id="cep"
                                          :class="getValidationClass('cep')"
                                          @change.native="buscaEnderecoPorCep()"
                                          v-model="form.agendamento.cep"
                                          :disabled="form.loading"
                                          :masked="true"
                                          :mask="['#####-###']"></the-mask>
                                <label for="cep">CEP</label>
                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.cep.required"></span>
                                <span class="helper-text" data-error="Mínimo de 9 caracteres!" v-else-if="!$v.form.agendamento.cep.minlength"></span>
                                <span class="helper-text" data-error="Máximo de 9 caracteres!" v-else-if="!$v.form.agendamento.cep.maxlength"></span>
                            </div>

                            <div class="input-field col m9 s12">
                                <input name="logradouro"
                                       id="logradouro"
                                       type="text"
                                       :class="getValidationClass('logradouro')"
                                       @change="resetValidationProp('logradouro')"
                                       maxlength="150"
                                       v-model="form.agendamento.logradouro"
                                       :disabled="form.loading"/>
                                <label for="logradouro">Logradouro</label>
                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.logradouro.required"></span>
                                <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.agendamento.logradouro.minlength"></span>
                                <span class="helper-text" data-error="Máximo de 150 caracteres!" v-else-if="!$v.form.agendamento.logradouro.maxlength"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col m3 s12">
                                <input name="numero_endereco"
                                       id="numero_endereco"
                                       type="text"
                                       placeholder="Opcional"
                                       maxlength="150"
                                       v-model="form.agendamento.numero_endereco"
                                       :disabled="form.loading"/>
                                <label for="numero_endereco">Número</label>
                            </div>

                            <div class="input-field col m4 s12">
                                <input name="complemento"
                                       id="complemento"
                                       type="text"
                                       placeholder="Opcional"
                                       maxlength="150"
                                       v-model="form.agendamento.complemento"
                                       :disabled="form.loading"/>
                                <label for="complemento">Complemento</label>
                            </div>

                            <div class="input-field col m5 s12">
                                <input name="bairro"
                                       id="bairro"
                                       type="text"
                                       :class="getValidationClass('bairro')"
                                       @change="resetValidationProp('bairro')"
                                       maxlength="150"
                                       v-model="form.agendamento.bairro"
                                       :disabled="form.loading"/>
                                <label for="bairro">Bairro</label>
                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.bairro.required"></span>
                                <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.agendamento.bairro.minlength"></span>
                                <span class="helper-text" data-error="Máximo de 150 caracteres!" v-else-if="!$v.form.agendamento.bairro.maxlength"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col m6 s12">
                                <input name="cidade"
                                       id="cidade"
                                       type="text"
                                       :class="getValidationClass('cidade')"
                                       @change="resetValidationProp('cidade')"
                                       maxlength="150"
                                       v-model="form.agendamento.cidade"
                                       :disabled="form.loading"/>
                                <label for="cidade">Cidade</label>
                                <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.agendamento.cidade.required"></span>
                                <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.agendamento.cidade.minlength"></span>
                                <span class="helper-text" data-error="Máximo de 150 caracteres!" v-else-if="!$v.form.agendamento.cidade.maxlength"></span>
                            </div>

                            <div class="input-field col m6 s12">
                                <select name="sel_agendamento_uf" id="sel_agendamento_uf"
                                        @change="resetValidationProp('agendamento_uf', true)"
                                        :disabled="form.loading"
                                        v-model="form.agendamento.ufs_id">
                                    <option value="" disabled>Selecione um Estado</option>
                                    <option v-for="(estadoBr, estadoBr_i) in estadosDoBrasil"
                                        :key="'uf_' + estadoBr_i"
                                            :title="estadoBr.sigla"
                                            :value="estadoBr.id">{{ estadoBr.nome }}</option>
                                </select>
                                <label>Estado (UF)</label>
                                <span class="helper-text" data-error="Campo obrigatório!" v-if="form.agendamento.ufs_id == null || form.agendamento.ufs_id === 0"></span>
                            </div>
                        </div>
                    </section>


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
        minLength,
        maxLength,
        requiredIf
    } from 'vuelidate/lib/validators'

    export default {
        name: "cadastra_edita_agendamento",
        data(){
            return {
                TipoAgendamento: {
                    VISITA : 1,
                    CHAMADA: 2,
                },

                estadosDoBrasil: [
                    {id: 1,  sigla: 'AC', nome: 'AC - Acre'},
                    {id: 2,  sigla: 'AL', nome: 'AL - Alagoas'},
                    {id: 3,  sigla: 'AM', nome: 'AM - Amazônia'},
                    {id: 4,  sigla: 'AP', nome: 'AP - Amapá'},
                    {id: 5,  sigla: 'BA', nome: 'BA - Bahia'},
                    {id: 6,  sigla: 'CE', nome: 'CE - Ceará'},
                    {id: 7,  sigla: 'DF', nome: 'DF - Distrito Federal (Brasília)'},
                    {id: 8,  sigla: 'ES', nome: 'ES - Espírito Santo'},
                    {id: 9,  sigla: 'GO', nome: 'GO - Goiás'},
                    {id: 10, sigla: 'MA', nome: 'MA - Maranhão'},
                    {id: 11, sigla: 'MG', nome: 'MG - Minas Gerais'},
                    {id: 12, sigla: 'MS', nome: 'MS - Mato Grosso do Sul'},
                    {id: 13, sigla: 'MT', nome: 'MT - Mato Grosso'},
                    {id: 14, sigla: 'PA', nome: 'PA - Pará'},
                    {id: 15, sigla: 'PB', nome: 'PB - Paraíba'},
                    {id: 16, sigla: 'PE', nome: 'PE - Pernambuco'},
                    {id: 17, sigla: 'PI', nome: 'PI - Piauí'},
                    {id: 18, sigla: 'PR', nome: 'PR - Paraná'},
                    {id: 19, sigla: 'RJ', nome: 'RJ - Rio de Janeiro'},
                    {id: 20, sigla: 'RN', nome: 'RN - Rio Grande do Norte'},
                    {id: 21, sigla: 'RO', nome: 'RO - Rondônia'},
                    {id: 22, sigla: 'RR', nome: 'RR - Roraima'},
                    {id: 23, sigla: 'RS', nome: 'RS - Rio Grande do Sul'},
                    {id: 24, sigla: 'SC', nome: 'SC - Santa Catarina'},
                    {id: 25, sigla: 'SE', nome: 'SE - Sergipe'},
                    {id: 26, sigla: 'SP', nome: 'SP - São Paulo'},
                    {id: 27, sigla: 'TO', nome: 'TO - Tocantins'}
                ],

                form: {
                    agendamento: {
                        id: null,
                        ficha_id: null,
                        tipo_id: null,
                        hora_agendamento: null,
                        data_agendamento: null,
                        data_hora_agendamento: null,
                        numero_telefone: null,
                        cep: null,
                        logradouro: null,
                        ufs_id: null,
                        users_id: null,
                        numero_endereco: null,
                        complemento: null,
                        bairro: null,
                        cidade: null,
                        deleted_at: null,
                        created_at: null,
                        updated_at: null,
                        ativo: true,
                        tipo_agendamento: null,
                        usuario: null,
                    },

                    loading: false,
                },

                abreJanelaForm: false,
                janelaForm: null,
            }
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
        validations() {
            const TipoAgendamento = this.TipoAgendamento

            return {
                form: {
                    agendamento: {
                        data_agendamento: {
                            required,
                        },
                        hora_agendamento: {
                            required,
                        },
                        numero_telefone: {
                            required,
                            minLength: minLength(14)
                        },
                        cep: {
                            minLength: minLength(9),
                            maxLength: maxLength(9),
                            required: requiredIf((agendamento) => {
                                return agendamento.tipo_id == TipoAgendamento.VISITA
                            }),
                        },
                        logradouro: {
                            minLength: minLength(2),
                            maxLength: maxLength(150),
                            required: requiredIf((agendamento) => {
                                return agendamento.tipo_id == TipoAgendamento.VISITA
                            }),
                        },
                        bairro: {
                            minLength: minLength(2),
                            maxLength: maxLength(150),
                            required: requiredIf((agendamento) => {
                                return agendamento.tipo_id == TipoAgendamento.VISITA
                            }),
                        },
                        cidade: {
                            minLength: minLength(2),
                            maxLength: maxLength(150),
                            required: requiredIf((agendamento) => {
                                return agendamento.tipo_id == TipoAgendamento.VISITA
                            }),
                        },
                    }
                }
            }


        },
        mounted() {
            const elemento = document.getElementById('modalFormCadastroAgendamento')
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
            $('#sel_agendamento_uf').formSelect()
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

            limpaCamposDeEndereco(limpaCep = true){
                if(limpaCep)
                    this.form.agendamento.cep = null


                this.form.agendamento.logradouro = null
                this.form.agendamento.numero_endereco = null
                this.form.agendamento.complemento = null
                this.form.agendamento.bairro = null
                this.form.agendamento.cidade = null
                this.form.agendamento.ufs_id = null
            },

            limpaCamposFormulario(){
                this.form.agendamento.id = null
                this.form.agendamento.users_id = null
                this.form.agendamento.ficha_id = null
                this.form.agendamento.tipo_id = null
                this.form.agendamento.hora_agendamento = null
                this.form.agendamento.data_agendamento = null
                this.form.agendamento.data_hora_agendamento = null
                this.form.agendamento.numero_telefone = null
                this.form.agendamento.ativo = true
                this.form.agendamento.tipo_agendamento = null
                this.form.agendamento.usuario = null
                this.form.agendamento.deleted_at = null
                this.form.agendamento.created_at = null
                this.form.agendamento.updated_at = null

                this.limpaCamposDeEndereco()

                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoAgendamento(ficha, usuarioLogado){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.agendamento.users_id = usuarioLogado.id
                this.form.agendamento.usuario = usuarioLogado
                this.form.agendamento.ficha_id = ficha.id

                this.abreJanela()
            },

            /*
            editaAgendamento(agendamentoParaEdicao){
                this.limpaMensagensDeErro()

                this.form.agendamento = agendamentoParaEdicao

                this.abreJanela()
            },
             */

            // Eventos de validação do formulário
            // Eventos de validação do formulário
            resetValidationProp(fieldName, isSelect = false){
                if(isSelect){
                    if (this.form.agendamento.tipo_id == this.TipoAgendamento.VISITA && this.form.agendamento.ufs_id != null && this.form.agendamento.ufs_id !== 0)
                        $('#sel_' + fieldName).parents('div.select-wrapper').removeClass('invalid')
                }else{
                    let fieldSplit = fieldName.split('.')
                    let fieldOrigin = this.$v.form['agendamento']
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
                let fieldOrigin = this.$v.form['agendamento']
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
                let agendamento_estado_valido = ((this.form.agendamento.tipo_id == this.TipoAgendamento.VISITA && this.form.agendamento.ufs_id != null && this.form.agendamento.ufs_id !== 0) || this.form.agendamento.tipo_id == this.TipoAgendamento.CHAMADA)
                if(!agendamento_estado_valido)
                    $('#sel_agendamento_uf').parents('div.select-wrapper').removeClass('invalid')

                this.$v.$touch()

                return (!this.$v.$invalid && agendamento_estado_valido)
            },
            // Eventos de validação do formulário

            salva(){
                if(this.valida()){
                    this.limpaMensagensDeErro()
                    this.form.loading = true

                    if(this.form.agendamento.id == null || this.form.agendamento.id == 0){
                        axios.post('/agendamentos', this.form.agendamento)
                            .then(response => {
                                this.form.loading = false
                                Swal.fire(
                                    'Ótimo!',
                                    response.data.msg,
                                    'success'
                                )
                                this.concluiSalvamentoDosDados(response.data.agendamentosSalvos)
                            })
                            .catch(error => {
                                $('#modalFormCadastroAgendamento').scrollTop(0)
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar criar essa agendamento, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }/*else{
                        axios.put('/agendamentos/' + this.form.agendamento.id, this.form.agendamento)
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
                                $('#modalCadastroFichaAgendamento').scrollTop(0)
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar cadastrar essa departamento, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }*/
                }
            },

            buscaEnderecoPorCep(){
                this.limpaMensagensDeErro()
                this.limpaCamposDeEndereco(false)

                this.resetValidationProp('cep')

                let cepInformado = _.replace(this.form.agendamento.cep, '-', '')

                if(cepInformado.length == 0)
                    return
                else if(cepInformado.length != 8){
                    Swal.fire('Oops!', 'O CEP informado é inválido.', 'error')
                }else{
                    this.form.loading = true

                    this.$viaCep.buscarCep(cepInformado).then((enderecoRetornado) => {
                        this.preencheDadosEnderecoAoConsultarCep(enderecoRetornado)
                        this.form.loading = false
                    }).catch((error) => {
                        Swal.fire('Oops!', 'O CEP ' + this.form.agendamento.cep + ' não foi encontrado. <br>Digite o endereço completo.', 'error')
                        this.limpaCamposDeEndereco()
                        this.form.loading = false
                    });
                }
            },
            preencheDadosEnderecoAoConsultarCep(enderecoRetornado){
                this.form.agendamento.logradouro = enderecoRetornado.logradouro
                this.form.agendamento.bairro = enderecoRetornado.bairro
                this.form.agendamento.cidade = enderecoRetornado.localidade
                this.form.agendamento.ufs_id = _.find(this.estadosDoBrasil, (estado) => {
                    return estado.sigla == enderecoRetornado.uf
                }).id
            },

            concluiSalvamentoDosDados(agendamentosSalvos){
                this.$emit('emissaoDeDadosSalvos', agendamentosSalvos)
                this.fechaJanela()
            }
        }
    }
</script>

<style scoped>
</style>
