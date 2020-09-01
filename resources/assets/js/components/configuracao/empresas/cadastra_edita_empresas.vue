<template>
    <div class="modal-cadastro">
        <div id="modalCadastroEmpresa" class="modal">
            <vue-loading :loading="form.loading" :is-full-page="false"></vue-loading>

            <div class="modal-content">
                <h4>{{form.empresa.id == 0 || form.empresa.id == null ? 'Cadastrar nova empresa' : 'Alterar empresa'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novalidate>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <the-mask name="cpf_cnpj"
                                      id="cpf_cnpj"
                                      ref="cpf_cnpj"
                                      :class="getValidationClass('cpf_cnpj')"
                                      @change.native="resetValidationProp('cpf_cnpj')"
                                      :masked="false"
                                      v-model="form.empresa.cpf_cnpj"
                                      :mask="['###.###.###-##', '##.###.###/####-##']"
                                      :disabled="form.loading"></the-mask>
                            <label for="cpf_cnpj">CPF/CNPJ</label>
                            <span class="helper-text" data-error="Campo obrigatório!"
                                  v-if="!$v.form.empresa.cpf_cnpj.required"></span>
                            <span class="helper-text" data-error="Inválido! O CPF/CNPJ tem que ter no mínimo 14 caracteres."
                                  v-else-if="!$v.form.empresa.cpf_cnpj.minlength"></span>
                            <span class="helper-text" data-error="Inválido! O CPF/CNPJ tem que ter no máximo 16 caracteres."
                                  v-else-if="!$v.form.empresa.cpf_cnpj.maxlength"></span>
                        </div>
                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="razao_social"
                                   id="razao_social"
                                   type="text"
                                   :class="getValidationClass('razao_social')"
                                   @change="resetValidationProp('razao_social')"
                                   maxlength="100"
                                   v-model="form.empresa.razao_social"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="razao_social">Razão Social</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.empresa.razao_social.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.empresa.razao_social.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 100 caracteres!" v-else-if="!$v.form.empresa.razao_social.maxlength"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome_fantasia"
                                   id="nome_fantasia"
                                   type="text"
                                   :class="getValidationClass('nome_fantasia')"
                                   @change="resetValidationProp('nome_fantasia')"
                                   maxlength="100"
                                   v-model="form.empresa.nome_fantasia"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome_fantasia">Nome Fantasia</label>
                            <span class="helper-text"
                                  data-error="Campo obrigatório!"
                                  v-if="!$v.form.empresa.nome_fantasia.required"></span>
                            <span class="helper-text"
                                  data-error="Mínimo de 2 caracteres!"
                                  v-else-if="!$v.form.empresa.nome_fantasia.minlength"></span>
                            <span class="helper-text"
                                  data-error="Máximo de 100 caracteres!"
                                  v-else-if="!$v.form.empresa.nome_fantasia.maxlength"></span>
                        </div>
                        <div class="input-field col xl6 l6 m6 s12">
                            <input type="number"
                                   name="quantidade_licencas"
                                   id="quantidade_licencas"
                                   :class="getValidationClass('quantidade_licencas')"
                                   @change="resetValidationProp('quantidade_licencas')"
                                   v-model="form.empresa.quantidade_licencas"
                                   :disabled="form.loading">
                            <label for="quantidade_licencas">Total de Licenças</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.empresa.quantidade_licencas.required"></span>
                            <span class="helper-text" data-error="Mínimo de 1 licença por empresa!" v-else-if="!$v.form.empresa.quantidade_licencas.minvalue"></span>
                        </div>
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
        minLength,
        maxLength,
        minValue
    } from 'vuelidate/lib/validators'

    export default {
        name: "cadastra_edita_empresas",
        data() {
            return {
                form: {
                    empresa: {
                        id: null,
                        cpf_cnpj: null,
                        razao_social: null,
                        nome_fantasia: null,
                        quantidade_licencas: 0,
                        ativo: true,
                    },

                    loading: false,
                },
                abreJanelaForm: false,
                janelaForm: null,
            }
        },
        mounted() {
            const elemento = document.getElementById('modalCadastroEmpresa')
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
        validations: {
            form: {
                empresa: {
                    razao_social: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(1024)
                    },
                    nome_fantasia: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(1024)
                    },
                    cpf_cnpj: {
                        required,
                        minLength: minLength(11),
                        maxLength: maxLength(14)
                    },
                    quantidade_licencas: {
                        required,
                        minValue: minValue(1),
                    },
                }
            }
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
                this.form.empresa.id = null
                this.form.empresa.cpf_cnpj = null
                this.$refs.cpf_cnpj.lastValue = null
                this.$refs.cpf_cnpj.display = null
                this.form.empresa.razao_social = null
                this.form.empresa.nome_fantasia = null
                this.form.empresa.quantidade_licencas = 0
                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovaEmpresa(){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()
                this.abreJanela()
            },

            editaEmpresa(empresaParaEdicao){
                this.limpaMensagensDeErro()

                this.form.empresa.id = empresaParaEdicao.id
                this.form.empresa.cpf_cnpj = empresaParaEdicao.cpf_cnpj
                this.form.empresa.razao_social = empresaParaEdicao.razao_social
                this.form.empresa.nome_fantasia = empresaParaEdicao.nome_fantasia
                this.form.empresa.quantidade_licencas = empresaParaEdicao.quantidade_licencas

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName){
                this.$v.form['empresa'][fieldName].$reset()
            },
            getValidationClass (fieldName) {
                const field = this.$v.form['empresa'][fieldName]

                if (field) {
                    return {
                        'invalid': field.$invalid && field.$dirty
                    }
                }
            },
            valida(){
                this.$v.$touch()

                return !this.$v.$invalid
            },
            // Eventos de validação do formulário

            salva(){
                if(this.valida()){
                    this.limpaMensagensDeErro()
                    this.form.loading = true

                    if(this.form.empresa.id == null || this.form.empresa.id == 0){
                        axios.post('/empresas', this.form.empresa)
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
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar cadastrar essa empresa, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/empresas/' + this.form.empresa.id, this.form.empresa)
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
                                this.form.loading = false
                                Swal.fire(
                                    'Oops!',
                                    'Ocorreu um erro ao tentar alterar essa empresa, feche para ver mais detalhes.',
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

</style>
