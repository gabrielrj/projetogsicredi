<template>
    <div class="modal-cadastro">
        <div id="modalCadastroCargo" class="modal">
            <vue-loading :loading="form.loading" :is-full-page="false"></vue-loading>

            <div class="modal-content">
                <h4>{{form.cargo.id == 0 || form.cargo.id == null ? 'Cadastrar novo cargo' : 'Alterar cargo'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="empresa" id="empresa"
                                   disabled
                                   :value="form.cargo.departamento.empresa != null && form.cargo.departamento.empresa.hasOwnProperty('razao_social') ? form.cargo.departamento.empresa.razao_social : null">
                            <label for="empresa">Empresa</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <select name="selectdepartamento" id="selectdepartamento"
                                    @change="resetValidationProp('departamento', true)"
                                    v-model="form.cargo.departamento.id">
                                <option value="" disabled>Selecione um departamento</option>
                                <option v-for="(departamento_ativo, departamento_ativo_i) in form.departamentosAtivos"
                                        :value="departamento_ativo.id"
                                        :title="departamento_ativo.nome"
                                        :key="'departamento_ativo_' + departamento_ativo_i">{{departamento_ativo.nome}}</option>
                            </select>
                            <label>Departamento</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="form.cargo.departamento.id == null || form.cargo.departamento.id === 0"></span>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome"
                                   id="nome"
                                   type="text"
                                   :class="getValidationClass('nome')"
                                   @change="resetValidationProp('nome')"
                                   maxlength="50"
                                   v-model="form.cargo.nome"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome">Nome do Cargo</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.cargo.nome.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.cargo.nome.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 100 caracteres!" v-else-if="!$v.form.cargo.nome.maxlength"></span>
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
        name: "cadastra_edita_cargos",
        data() {
            return {
                form: {
                    cargo: {
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
            const elemento = document.getElementById('modalCadastroCargo')
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
                cargo: {
                    nome: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(100)
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
                this.form.cargo.id = null
                this.form.cargo.nome = null
                this.form.cargo.departamento.id = null
                this.form.cargo.departamento.nome = null
                this.form.cargo.departamento.empresa = null
                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoCargo(empresa, departamentosAtivos){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.departamentosAtivos = departamentosAtivos
                this.form.cargo.departamento.empresa = empresa

                this.abreJanela()
            },

            editaCargo(cargoParaEdicao, departamentosAtivos){
                this.limpaMensagensDeErro()

                this.form.departamentosAtivos = departamentosAtivos
                this.form.cargo.id = cargoParaEdicao.id
                this.form.cargo.nome = cargoParaEdicao.nome
                this.form.cargo.departamento.id = cargoParaEdicao.departamento.id
                this.form.cargo.departamento.nome = cargoParaEdicao.departamento.nome
                this.form.cargo.departamento.empresa = cargoParaEdicao.departamento.empresa

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName, isSelect = false){
                if(!isSelect) {
                    const field = this.$v.form['cargo'][fieldName]

                    field.$reset()
                }else {
                    if (this.form.cargo.departamento.id != null && this.form.cargo.departamento.id !== 0)
                        $('#select' + fieldName).parents('div.select-wrapper').removeClass('invalid')
                }

            },
            getValidationClass (fieldName) {
                const field = this.$v.form['cargo'][fieldName]

                if (field) {

                    return {
                        'invalid': field.$invalid && field.$dirty
                    }
                }
            },
            valida(){
                let departamento_valido = this.form.cargo.departamento.id != null && this.form.cargo.departamento.id !== 0
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

                    if(this.form.cargo.id == null || this.form.cargo.id == 0){
                        axios.post('/cargos', this.form.cargo)
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
                                    'Ocorreu um erro ao tentar cadastrar esse cargo, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/cargos/' + this.form.cargo.id, this.form.cargo)
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
                                    'Ocorreu um erro ao tentar alterar esse cargo, feche para ver mais detalhes.',
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
