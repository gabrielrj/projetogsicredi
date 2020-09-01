<template>
    <div class="modal-cadastro">
        <div id="modalCadastroPerfil" class="modal">
            <div class="modal-content">
                <vue-loading :loading="form.loading" :is-full-page="true"></vue-loading>

                <h4>{{form.perfil.id == 0 || form.perfil.id == null ? 'Cadastrar novo perfil' : 'Alterar perfil'}}</h4>

                <vue-alert ref="alert"></vue-alert>

                <form novolidate>
                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <input type="text" name="empresa" id="empresa"
                                   disabled
                                   :value="form.perfil.empresa != null && form.perfil.empresa.hasOwnProperty('razao_social') ? form.perfil.empresa.razao_social : null">
                            <label for="empresa">Empresa</label>
                        </div>
                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome"
                                   id="nome"
                                   type="text"
                                   :class="getValidationClass('nome')"
                                   @change="resetValidationProp('nome')"
                                   maxlength="50"
                                   v-model="form.perfil.nome"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome">Nome do Perfil</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.perfil.nome.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.perfil.nome.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 200 caracteres!" v-else-if="!$v.form.perfil.nome.maxlength"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="card-panel">
                                <h5 class="center">Permissões de Acesso</h5>

                                <tree ref="arvore_permissao"
                                      v-model="form.perfil.permissoesSelecionadas"
                                      :options="form.opcoesArvoreDePermissoes"
                                      :data="form.arvoreDePermissoes"></tree>
                            </div>
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
        name: "cadastra_edita_perfis",
        props: {
            funcoesDeAcesso: {
                type: Array,
                default: null,
            }
        },
        data() {
            return {
                form: {
                    perfil: {
                        id: null,
                        nome: null,
                        empresa: null,
                        permissoesSelecionadas: null,
                    },

                    arvoreDePermissoes: this.geraArvorePermissoes(),
                    opcoesArvoreDePermissoes: {
                        checkbox: true
                    },

                    loading: false,
                },
                abreJanelaForm: false,
                janelaForm: null,
            }
        },
        mounted() {
            const elemento = document.getElementById('modalCadastroPerfil')
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
            },


        },
        validations: {
            form: {
                perfil: {
                    nome: {
                        required,
                        minLength: minLength(2),
                        maxLength: maxLength(200)
                    },
                }
            }
        },
        methods: {

            //Eventos da arvore de permissões
            geraArvorePermissoes(){
                let arvore = []

                _.forEach(this.funcoesDeAcesso, (menu) => {
                    arvore.push({
                        state: {expanded: true},
                        id: menu.id,
                        text: menu.titulo + ' (Menu)',
                        data: {tipo: 'menu'},
                        children: this.retornaSubmenusEFuncoes(menu.submenus, menu.funcoes_de_acesso)
                    })
                })

                return arvore
            },
            retornaSubmenusEFuncoes(submenus, funcoes){
                let retorno = []

                if(funcoes != null && funcoes.length > 0){
                    _.forEach(funcoes, (funcao) => {
                        retorno.push({
                            state: {expanded: true},
                            id: funcao.id,
                            data: {tipo: 'funcao'},
                            text: funcao.titulo + ' (Tela)'
                        })
                    })
                }

                if(submenus != null && submenus.length > 0){
                    _.forEach(submenus, (sub) => {
                        retorno.push({
                            state: {expanded: true},
                            id: sub.id,
                            text: sub.titulo + ' (Submenu)',
                            data: {tipo: 'menu'},
                            children: this.retornaSubmenusEFuncoes(null, sub.funcoes_de_acesso)
                        })
                    })
                }


                return retorno
            },

            retornaFuncaoChecadaOuNao(funcao_id){
                if(this.form.perfil.id == null || this.form.perfil.id === 0)
                    return false
                else{
                    let funcaoJaCadastrada = _.find(this.form.perfil.funcoes_de_acesso, (permissao) => {
                        return permissao.id == funcao_id
                    })

                    return (funcaoJaCadastrada != undefined && funcaoJaCadastrada != null)
                }
            },
            retornaFuncoesSelecionadas(){
                let funcoesSelecionadas = []

                if(this.form.perfil.permissoesSelecionadas != null && this.form.perfil.permissoesSelecionadas.checked.length > 0){
                    let permissoes = _.filter(this.form.perfil.permissoesSelecionadas.checked, (item) => {
                        return item.data.tipo == 'funcao'
                    })

                    _.forEach(permissoes, (item) => {
                        funcoesSelecionadas.push(item.id)
                    })
                }

                return funcoesSelecionadas
            },
            preenchePermissionamentoDoPerfilParaEdicao(perfilParaEdicao){
                if(perfilParaEdicao != null && perfilParaEdicao != undefined){
                    this.form.perfil.id = perfilParaEdicao.id
                    this.form.perfil.nome = perfilParaEdicao.nome
                    this.form.perfil.empresa = perfilParaEdicao.empresa

                    let self = this

                    _.forEach(perfilParaEdicao.funcoes_de_acesso, (permissao) => {
                        let noDePermissao = self.$refs.arvore_permissao.find({text: (permissao.titulo + ' (Tela)')})

                        noDePermissao.check(true)
                    })
                }else{
                    this.limpaCamposFormulario()
                }
            },

            // Eventos do form
            abreJanela(){
                this.abreJanelaForm = true
            },

            fechaJanela(){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()
                this.abreJanelaForm = false
            },

            eventoDeFechamentoDeJanela(){
                this.limpaCamposFormulario()
                this.abreJanelaForm = false
            },

            limpaCamposFormulario(){
                this.form.perfil.id = null
                this.form.perfil.nome = null
                this.form.perfil.empresa = null
                this.form.perfil.permissoesSelecionadas = null
                this.$refs.arvore_permissao.checked().uncheck()
                this.form.loading = false
            },

            limpaMensagensDeErro(){
                this.$v.$reset()
                this.$refs.alert.limpaMensagens()
            },

            cadastraNovoPerfil(empresa){
                this.limpaCamposFormulario()
                this.limpaMensagensDeErro()

                this.form.perfil.empresa = empresa

                this.abreJanela()
            },

            editaPerfil(perfilParaEdicao){
                this.limpaMensagensDeErro()

                this.preenchePermissionamentoDoPerfilParaEdicao(perfilParaEdicao)

                this.abreJanela()
            },

            // Eventos de validação do formulário
            resetValidationProp(fieldName){
                this.$v.form['perfil'][fieldName].$reset()
            },
            getValidationClass (fieldName) {
                const field = this.$v.form['perfil'][fieldName]

                if (field) {
                    return {
                        'invalid': field.$invalid && field.$dirty
                    }
                }
            },
            valida(){
                let validaPermissoesPreenchidas = this.retornaFuncoesSelecionadas().length > 0

                if(!validaPermissoesPreenchidas)
                    Swal.fire('Perae!', 'É necessário selecionar ao menus uma permissão de acesso para cadastrar um perfil.', 'error')

                this.$v.$touch()

                return (!this.$v.$invalid && validaPermissoesPreenchidas)
            },
            // Eventos de validação do formulário

            salva(){
                if(this.valida()){
                    this.limpaMensagensDeErro()

                    let permissoes = this.retornaFuncoesSelecionadas()

                    this.form.loading = true

                    if(this.form.perfil.id == null || this.form.perfil.id == 0){
                        axios.post('/perfis', {'empresa_id' : this.form.perfil.empresa.id, 'nome' : this.form.perfil.nome, 'permissoes' : permissoes})
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
                                    'Ocorreu um erro ao tentar cadastrar esse perfil, feche para ver mais detalhes.',
                                    'error'
                                )
                                this.$refs.alert.abreMensagens(error.response)
                            })
                    }else{
                        axios.put('/perfis/' + this.form.perfil.id, {'empresa_id' : this.form.perfil.empresa.id, 'nome' : this.form.perfil.nome, 'permissoes' : permissoes})
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
                                    'Ocorreu um erro ao tentar alterar esse perfil, feche para ver mais detalhes.',
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
