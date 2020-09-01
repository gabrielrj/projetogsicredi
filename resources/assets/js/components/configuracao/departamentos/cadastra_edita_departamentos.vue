<template>
    <div class="modal-cadastro">
        <div id="modalCadastroDepartamento" class="modal">
            <vue-loading :loading="form.loading" :is-full-page="false"></vue-loading>

            <div class="modal-content">
                <h4>{{form.departamento.id == 0 || form.departamento.id == null ? 'Cadastrar novo departamento' : 'Alterar departamento'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <input type="text" name="empresa" id="empresa"
                                   disabled
                                   :value="form.departamento.empresa != null && form.departamento.empresa.hasOwnProperty('razao_social') ? form.departamento.empresa.razao_social : null">
                            <label for="empresa">Empresa</label>
                        </div>
                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome"
                                   id="nome"
                                   type="text"
                                   :class="getValidationClass('nome')"
                                   @change="resetValidationProp('nome')"
                                   maxlength="50"
                                   v-model="form.departamento.nome"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome">Nome do Departamento</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.departamento.nome.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.departamento.nome.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 50 caracteres!" v-else-if="!$v.form.departamento.nome.maxlength"></span>
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
        name: "cadastra_edita_departamentos",
        data() {
            return {
                form: {
                    departamento: {
                        id: null,
                        nome: null,
                        empresa: null,
                    },

                    loading: false,
                },
                abreJanelaForm: false,
                janelaForm: null,
            }
        },
        mounted() {
            const elemento = document.getElementById('modalCadastroDepartamento')
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
                departamento: {
                    nome: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(50)
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
                this.form.departamento.id = null
                this.form.departamento.nome = null
                this.form.departamento.empresa = null
                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoDepartamento(empresa){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.departamento.empresa = empresa

                this.abreJanela()
            },

            editaDepartamento(departamentoParaEdicao){
                this.limpaMensagensDeErro()

                this.form.departamento.id = departamentoParaEdicao.id
                this.form.departamento.nome = departamentoParaEdicao.nome
                this.form.departamento.empresa = departamentoParaEdicao.empresa

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName){
                this.$v.form['departamento'][fieldName].$reset()
            },
            getValidationClass (fieldName) {
                const field = this.$v.form['departamento'][fieldName]

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

                    if(this.form.departamento.id == null || this.form.departamento.id == 0){
                        axios.post('/departamentos', this.form.departamento)
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
                                    'Ocorreu um erro ao tentar cadastrar esse departamento, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/departamentos/' + this.form.departamento.id, this.form.departamento)
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
                                    'Ocorreu um erro ao tentar alterar esse departamento, feche para ver mais detalhes.',
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
