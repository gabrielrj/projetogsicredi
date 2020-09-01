<template>
    <div class="modal-cadastro">
        <div id="modalCadastroEquipe" class="modal">
            <vue-loading :loading="form.loading" :is-full-page="false"></vue-loading>

            <div class="modal-content">
                <h4>{{form.equipe.id == 0 || form.equipe.id == null ? 'Cadastrar nova equipe' : 'Alterar equipe'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="empresa" id="empresa"
                                   disabled
                                   :value="form.equipe.departamento.empresa != null && form.equipe.departamento.empresa.hasOwnProperty('razao_social') ? form.equipe.departamento.empresa.razao_social : null">
                            <label for="empresa">Empresa</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <select name="selectdepartamento" id="selectdepartamento"
                                    @change="resetValidationProp('departamento', true)"
                                    v-model="form.equipe.departamento.id">
                                <option value="" disabled>Selecione um departamento</option>
                                <option v-for="(departamento_ativo, departamento_ativo_i) in form.departamentosAtivos"
                                        :value="departamento_ativo.id"
                                        :title="departamento_ativo.nome"
                                        :key="'departamento_ativo_' + departamento_ativo_i">{{departamento_ativo.nome}}</option>
                            </select>
                            <label>Departamento</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="form.equipe.departamento.id == null || form.equipe.departamento.id === 0"></span>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome"
                                   id="nome"
                                   type="text"
                                   :class="getValidationClass('nome')"
                                   @change="resetValidationProp('nome')"
                                   maxlength="50"
                                   v-model="form.equipe.nome"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome">Nome da Equipe</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.equipe.nome.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.equipe.nome.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 50 caracteres!" v-else-if="!$v.form.equipe.nome.maxlength"></span>
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
    import {maxLength, minLength, required} from "vuelidate/lib/validators";

    export default {
        name: "cadastra_edita_equipes",
        data() {
            return {
                form: {
                    equipe: {
                        id: null,
                        nome: null,
                        departamento: {
                            id: null,
                            nome: null,
                            empresa: null,
                        },
                    },

                    departamentosAtivos: [],

                    loading: false,
                },
                abreJanelaForm: false,
                janelaForm: null,
            }
        },
        mounted() {
            const elemento = document.getElementById('modalCadastroEquipe')
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
            $('#selectdepartamento').formSelect()
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
                equipe: {
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
                this.form.departamentosAtivos.splice(0, this.form.departamentosAtivos.length)
                this.form.equipe.id = null
                this.form.equipe.nome = null
                this.form.equipe.departamento.id = null
                this.form.equipe.departamento.nome = null
                this.form.equipe.departamento.empresa = null
                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoEquipe(empresa, departamentosAtivos){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.departamentosAtivos = departamentosAtivos
                this.form.equipe.departamento.empresa = empresa

                this.abreJanela()
            },

            editaEquipe(equipeParaEdicao, departamentosAtivos){
                this.limpaMensagensDeErro()

                this.form.departamentosAtivos = departamentosAtivos
                this.form.equipe.id = equipeParaEdicao.id
                this.form.equipe.nome = equipeParaEdicao.nome
                this.form.equipe.departamento.id = equipeParaEdicao.departamento.id
                this.form.equipe.departamento.nome = equipeParaEdicao.departamento.nome
                this.form.equipe.departamento.empresa = equipeParaEdicao.departamento.empresa

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName, isSelect = false){
                if(!isSelect) {
                    const field = this.$v.form['equipe'][fieldName]

                    field.$reset()
                }else {
                    if (this.form.equipe.departamento.id != null && this.form.equipe.departamento.id !== 0)
                        $('#select' + fieldName).parents('div.select-wrapper').removeClass('invalid')
                }

            },
            getValidationClass (fieldName) {
                const field = this.$v.form['equipe'][fieldName]

                if (field) {

                    return {
                        'invalid': field.$invalid && field.$dirty
                    }
                }
            },
            valida(){
                let departamento_valido = this.form.equipe.departamento.id != null && this.form.equipe.departamento.id !== 0
                if(!departamento_valido)
                    $('#selectdepartamento').parents('div.select-wrapper').addClass('invalid')

                this.$v.$touch()

                return (!this.$v.$invalid && departamento_valido)
            },
            // Eventos de validação do formulário

            salva(){
                if(this.valida()){
                    this.limpaMensagensDeErro()
                    this.form.loading = true

                    if(this.form.equipe.id == null || this.form.equipe.id == 0){
                        axios.post('/equipes', this.form.equipe)
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
                                    'Ocorreu um erro ao tentar cadastrar essa equipe, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/equipes/' + this.form.equipe.id, this.form.equipe)
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
                                    'Ocorreu um erro ao tentar alterar essa equipe, feche para ver mais detalhes.',
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
