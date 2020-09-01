<template>
    <div>
        <!-- Modal Structure -->
        <div class="modal-form-login">

            <div id="modalFormLogin" class="modal">
                <vue-loading :loading="form.loading" :is-full-page="false"></vue-loading>

                <div class="modal-content">
                    <vue-alert ref="alert"></vue-alert>

                    <div class="hide-on-small-and-down center">
                        <img class="z-depth-5" src="img/logo_gsi_credi_50_50.png">
                    </div>
                    <div class="row">
                        <div class="col s12 center-align">
                            <h3 class="light">Autenticar</h3>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">account_balance</i>
                            <input type="text" id="codigo_empresa"
                                   :class="{'invalid' : form.erro.empresa != ''}"
                                   @keyup.enter="auth"
                                   v-model="form.usuario.empresa_id">
                            <label for="codigo_empresa">Código da Empresa</label>
                            <span :data-error="form.erro.empresa" class="helper-text"></span>
                        </div>
                        <div class="input-field col xl6 l6 m12 s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input type="text" id="login_ou_cpf"
                                   :class="{'invalid' : form.erro.login != ''}"
                                   maxlength="250"
                                   @keyup.enter="auth"
                                   v-model="form.usuario.login">
                            <label for="login_ou_cpf">Login/CPF</label>
                            <span :data-error="form.erro.login" class="helper-text"></span>
                        </div>
                        <div class="input-field col xl6 l6 m12 s12">
                            <i class="fas fa-key prefix"></i>
                            <!--i class="material-icons prefix">security</i-->
                            <input type="password" id="senha"
                                   :class="{'invalid' : form.erro.password != ''}"
                                   @keyup.enter="auth"
                                   v-model="form.usuario.password">
                            <label for="senha">Senha</label>
                            <span :data-error="form.erro.password" class="helper-text"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn red darken-1 waves-effect waves-light" @click="fechaJanelaDeLogin">
                        <i class="material-icons left">cancel</i>
                        CANCELAR
                    </a>
                    <a class="btn blue darken-4 waves-effect waves-light"
                       @keyup.enter="auth"
                       @click="auth"
                       :disabled="form.loading">
                        <i class="fas fa-lock-open left"></i>
                        ENTRAR
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "form_login",
        data() {
            return {
                form: {
                    usuario: {
                        empresa_id: null,
                        login: null,
                        password: null
                    },
                    erro: {
                        empresa: '',
                        login: '',
                        password: '',
                        other: ''
                    },
                    loading: false,
                },
                janelaFormLogin: null,
                abreJanelaFormLogin: false,
            }
        },
        mounted() {
            const elemento = document.getElementById('modalFormLogin')
            //const instancia = M.Modal.init(elemento, {dismissible: false})
            const instancia = M.Modal.init(elemento, {
                onCloseEnd: () => {
                    this.eventoDeFechamentoDeJanelaDeLogin()
                }
            })
            this.janelaFormLogin = instancia
        },
        watch: {
            abreJanelaFormLogin(newValue) {
                if(newValue) {
                    this.janelaFormLogin.open()
                }else{
                    this.janelaFormLogin.close()
                    this.janelaFormLogin.destroy()
                }
            }
        },
        methods: {
            abreJanelaDeLogin(){
                this.abreJanelaFormLogin = true
            },
            fechaJanelaDeLogin(){
                this.limpaCampos()
                this.limpaMensagensDeErro()
                this.form.loading = false
                this.abreJanelaFormLogin = false
            },
            eventoDeFechamentoDeJanelaDeLogin(){
                this.abreJanelaFormLogin = false
            },
            limpaCampos(){
                this.form.usuario.empresa_id = null
                this.form.usuario.login = null
                this.form.usuario.password = null
            },
            limpaMensagensDeErro(){
                this.$refs.alert.limpaMensagens()
                this.form.erro.empresa = ''
                this.form.erro.login = ''
                this.form.erro.password = ''
                this.form.erro.other = ''
            },
            auth() {
                this.limpaMensagensDeErro()
                // your code to login user
                // this is only for example of loading
                this.form.loading = true;

                axios.post('/login', {'empresa_id' : this.form.usuario.empresa_id, 'login' : this.form.usuario.login, 'password' : this.form.usuario.password})
                    .then(response => {
                        this.concluiLoginUsuario(response.data.userConfig)
                    })
                    .catch(error => {
                        this.form.loading = false

                        if(error.response.data.campo == 'empresa')
                            this.form.erro.empresa = error.response.data.msg
                        if(error.response.data.campo == 'login')
                            this.form.erro.login = error.response.data.msg
                        if(error.response.data.campo == 'password')
                            this.form.erro.password = error.response.data.msg
                        if(error.response.data.campo == 'other')
                            this.form.erro.other = error.response.data.msg

                        this.$refs.alert.abreMensagens(error.response)

                        window.scroll(0,0)
                    })
            },
            concluiLoginUsuario(usuarioLogado){
                window.location.replace('/home')

                /*let possuiPermissaoParaPainel = _.filter(usuarioLogado.perfil.funcoes_de_acesso, function (funcao) {
                    return funcao.rota == '/painel'
                }).length > 0

                if(possuiPermissaoParaPainel)
                    window.location.replace('/painel')
                else
                    window.location.replace('/home')

                 */
            },
        }
    }
</script>

<style scoped>
</style>
