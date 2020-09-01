<template>
    <div>
        <div class="row" v-if="listaMensagens.length > 0">
            <section v-if="listaMensagens.length == 1">
                <div class="col s12" v-for="(msg, i) in listaMensagens">
                    <div :id="'alert' + msg.tipo_mensagens + i"
                         class="card-panel lighten-4 text-darken-4"
                         :class="{'red red-text' : msg.tipo_mensagens == 'error', 'green green-text' : msg.tipo_mensagens == 'success'}">
                        <span class="fechar" @click="apagaMensagem(msg.id)">X</span>
                        <b><i class="small material-icons left">{{msg.tipo_mensagens == 'success' ? 'done' : 'error'}}</i>{{msg.tipo_mensagens == 'success' ? 'Sucesso!' : 'Erro!'}}</b> {{msg.mensagem}}
                    </div>
                </div>
            </section>

            <div class="col s12" v-else-if="listaMensagens.length > 1">
                <div class="card-panel lighten-4 text-darken-4"
                     id="alert_grupo"
                     :class="{'red red-text' : listaMensagens[0].tipo_mensagens == 'error', 'green green-text' : listaMensagens[0].tipo_mensagens == 'success'}">
                    <span class="fechar" @click="apagaMensagem('alert_grupo')">X</span>
                    <b><i class="small material-icons left">{{listaMensagens[0].tipo_mensagens == 'success' ? 'done' : 'error'}}</i>{{listaMensagens[0].tipo_mensagens == 'success' ? 'Sucesso!' : 'Erro!'}}</b>
                    <div v-if="listaMensagens[0].tipo_mensagens == 'success'">
                        <p>Você tem as seguintes mensagens do sistema: </p>
                        <ul class="browser-default">
                            <li v-for="(msg, i) in listaMensagens">{{msg.mensagem}}</li>
                        </ul>
                    </div>

                    <div v-else>
                        <p>Os seguintes erros forão encontrados: </p>
                        <ul class="browser-default">
                            <li v-for="(msg, i) in listaMensagens">{{msg.mensagem}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "alert",
        data() {
            return {
                listaDeMensagens: [],
            }
        },
        computed: {
            listaMensagens(){
                return _.orderBy(this.listaDeMensagens, 'mensagem')
            }
        },
        methods: {
            abreMensagens(mensagens){
                this.limpaMensagens()

                let resposta = ''

                try{
                     resposta = JSON.parse(mensagens)
                }catch (e) {
                    resposta = mensagens
                }

                console.log(resposta)

                if(resposta != null && resposta != undefined){
                    if(typeof resposta.data.errors != "undefined") {
                        for (var [key, value] of Object.entries(resposta.data.errors)) {
                            _.forEach(value, (v) => {
                                this.listaDeMensagens.push({
                                    id: this.listaDeMensagens.length + 1,
                                    mensagem: v,
                                    tipo_mensagens: 'error',
                                })
                            })
                        }
                    }else if(resposta.status != undefined && resposta.status != null){
                        this.listaDeMensagens.push({
                            id: this.listaDeMensagens.length + 1,
                            mensagem: resposta.data.msg != undefined && resposta.data.msg != null ? resposta.data.msg : 'Ocorreu um erro inesperado, informe ao administrador do sistema.',
                            tipo_mensagens: resposta.status < 200 || resposta.status > 299 ? 'error' : 'success'
                        })
                    }else if(typeof resposta.data.status_resposta !== "undefined"){
                        this.listaDeMensagens.push({
                            id: this.listaDeMensagens.length + 1,
                            mensagem: resposta.data.msg,
                            tipo_mensagens: resposta.data.status_resposta,
                        })
                    }else if(typeof resposta.data.message != "undefined"){
                        this.listaDeMensagens.push({
                            id: this.listaDeMensagens.length + 1,
                            mensagem: resposta.data.message,
                            tipo_mensagens: resposta.data.status_resposta
                        })
                    }else if(typeof resposta.data.msg != "undefined"){
                        this.listaDeMensagens.push({
                            id: this.listaDeMensagens.length + 1,
                            mensagem: resposta.data.msg,
                            tipo_mensagens: resposta.data.status_resposta
                        })
                    }else if(resposta.status_resposta != undefined){
                        this.listaDeMensagens.push({
                            id: this.listaDeMensagens.length + 1,
                            mensagem: resposta.msg,
                            tipo_mensagens: resposta.status_resposta
                        })
                    }

                    window.scroll(0, 0)
                }
            },
            limpaMensagens(){
                this.listaDeMensagens.splice(0, this.listaDeMensagens.length)
            },
            apagaMensagem(id){
                if(id == 'alert_grupo'){
                    this.limpaMensagens()
                }else{
                    let indiceDaMensagem = _.findIndex(this.listaDeMensagens, (msg) => {
                        return msg.id == id
                    })

                    this.listaDeMensagens.splice(indiceDaMensagem, 1)
                }
            },
        }
    }
</script>

<style scoped>

</style>
