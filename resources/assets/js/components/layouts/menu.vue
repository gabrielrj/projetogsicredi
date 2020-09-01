<template>
    <div>
        <!-- Menu Telas Grandes -->
        <nav class="nav-extended indigo darken-4">
            <!-- Linha Primária (Nome, Alto-contraste e Zoom) -->
            <div class="nav-wrapper">
                <!-- LOGO -->
                <span id="logo-container" class="brand-logo">
                    <img class="left-align" src="/img/logo_gsi_credi_fundo_transparente_50px.png" />
                </span>

                <a tabindex="0" href="#" data-target="slide-out" class="sidenav-trigger">
                    <i class="material-icons white-text">menu</i>
                </a>

                <ul class="right hide-on-med-and-down">
                    <!-- CASO USUARIO NÃO ESTEJA LOGADO APARECE SOMENTE O MENU PARA ACESSO -->
                    <li v-if="usuarioLogado == null || usuarioLogado == '' || usuarioLogado == undefined">
                        <a tabindex="0" class="white-text" @click="login()">Acessar</a>
                    </li>
                    <!-- CASO USUÁRIO ESTEJA LOGADO APARECE SEU NOME COM AS OPÇÕES DE (SAIR, SEUS DADOS, ETC..) -->
                    <div v-else style="display: inline;">
                        <li>
                            <a tabindex="0" class="dropdown-trigger white-text" href="#!" data-target="ddLogin">
                                {{usuarioLogado.name}}<i class="material-icons right">arrow_drop_down</i>
                            </a>
                        </li>
                        <ul id="ddLogin" class="dropdown-content">
                            <li>
                                <a tabindex="0" href="/logout">Sair<i class="small material-icons">exit_to_app</i></a>
                            </li>
                        </ul>
                    </div>

                    <!-- Menu de Acessibilidade -->
                    <li class="valign-wrapper" style="font-size: 15px;">
                        <a role="button" tabindex="0" id="altocontraste" title="Habilitar alto contraste" class="center white-text" style="text-decoration: none !important;" accesskey="4" onclick="window.toggleContrast()">
                            <i class="small material-icons">invert_colors</i>
                        </a>
                    </li>
                    <li class="valign-wrapper" style="font-size: 15px;">
                        <a role="button" tabindex="0" class="center white-text" title="Aumentar fonte" accesskey="5" @click="fonte('a');" style="border: 0;">A+</a>
                    </li>
                    <li class="valign-wrapper" style="font-size: 15px;">
                        <a role="button" tabindex="0" class="center white-text" title="Diminuir fonte" accesskey="7" @click="fonte('d');" style="border: 0;">A-</a>
                    </li>
                    <li class="valign-wrapper" style="font-size: 15px;">
                        <a role="button" tabindex="0" class="center white-text" title="Resetar fonte" accesskey="8" @click="fonte('r');" style="border: 0;">A</a>
                    </li>
                    <!-- FIM MENU ACESSIBILIDADE -->

                </ul>
            </div>
            <!-- Linha Secundária (Menu de funcionalidades) -->
            <div class="nav-content" v-if="usuarioLogado != null && usuarioLogado != undefined">
                <nav class="hide-on-med-and-down">
                    <div class="nav-wrapper indigo darken-4">
                        <ul class="right">
                            <!-- CASO O USUÁRIO ESTEJA LOGADO É MONTADO O MENU AO QUAL TEM ACESSO -->
                            <li>
                                <a tabindex="0" href="/" title="Página inicial"><i class="small material-icons white-text">home</i></a>
                            </li>
                            <li v-if="usuarioLogado != null && usuarioLogado != undefined && usuarioLogado.perfil.super_user">
                                <a tabindex="0" href="/empresas" title="Empresas" class="white-text"><i class="material-icons left">business</i>Empresas</a>
                            </li>

                            <div v-for="(m, menu_i) in retornaMenusESubmenus" style="display: inline;">
                                <ul class="dropdown-content" :id="'ddMenu_' + m.menu.id" v-if="m.submenus.length > 0">
                                    <div v-for="(submenu, submenu_i) in m.submenus">
                                        <li>
                                            <a tabindex="0" :href="submenu.funcao_de_acesso_principal.rota">
                                                <i class="material-icons left"
                                                   v-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'material'">{{submenu.icone}}</i>
                                                <i class="left"
                                                   v-else-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'fontawesome'"
                                                   :class="submenu.icone"
                                                   v-else></i>&nbsp;&nbsp;
                                                {{submenu.titulo}}
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                    </div>
                                </ul>
                                <li v-if="m.submenus.length > 0">
                                    <a tabindex="0" class="dropdown-trigger white-text" href="#!" :data-target="'ddMenu_' + m.menu.id">
                                        <i class="material-icons left"
                                           v-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                                        <i class="left"
                                           v-else-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'fontawesome'"
                                           :class="m.menu.icone"
                                           v-else></i>&nbsp;&nbsp;
                                        {{m.menu.titulo}}
                                        <i class="material-icons right">arrow_drop_down</i>
                                    </a>
                                </li>

                                <li v-else>
                                    <a tabindex="0" :href="m.menu.funcao_de_acesso_principal.rota" class="white-text">
                                        <i class="material-icons left"
                                           v-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                                        <i class="left"
                                           v-else-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'fontawesome'"
                                           :class="m.menu.icone"
                                           v-else></i>&nbsp;&nbsp;
                                        {{m.menu.titulo}}
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>

        <!-- Menu Telas Pequenas -->
        <ul id="slide-out" class="sidenav indigo darken-4">
            <!-- Menu de Acessibilidade -->
            <li class="acessibilidade-mobile">
                <a href="#" role="button" tabindex="0" id="altocontraste2" title="Habilitar alto contraste" accesskey="4" onclick="window.toggleContrast()"><span><i class="material-icons">invert_colors</i></span></a>
                <a href="#" role="button" tabindex="0" title="Aumentar fonte" accesskey="5" @click="fonte('a');"><span>A+</span></a>
                <a href="#" role="button" tabindex="0" title="Diminuir fonte" accesskey="7" @click="fonte('d');"><span tabindex="0">A-</span></a>
                <a href="#" role="button" tabindex="0" title="Resetar fonte" accesskey="8" @click="fonte('r');"><span>A</span></a>
            </li>

            <li>
                <div class="user-view">
                    <a id="logo-container2" tabindex="0" class="brand-logo">
                        <img class="left-align" src="/img/logo_gsi_credi_fundo_transparente_80px.png"/>
                    </a>

                    <div v-if="usuarioLogado != null && usuarioLogado != undefined">
                        <span class="name">{{usuarioLogado.name}}</span>
                        <span class="email" v-if="usuarioLogado.email != null && usuarioLogado.email != ''">{{usuarioLogado.email}}</span>
                    </div>

                </div>
            </li>

            <!-- Caso não esteja logado é mostrado o menu de Acessar -->
            <li v-if="usuarioLogado == null || usuarioLogado == undefined">
                <a tabindex="0" class="white-text" @click="login()">Acessar</a>
            </li>
            <!-- Caso esteja logado é montado os menus que o usuário tem acesso -->
            <div v-else>
                <li>
                    <a tabindex="0" href="/">Pagina Inicial</a>
                </li>
                <li v-if="usuarioLogado.perfil.super_user == 1 || usuarioLogado.perfil.super_user == true">
                    <a tabindex="0" href="/empresas">Empresas<i class="material-icons left">business</i></a>
                </li>

                <div v-for="(m, menu_i) in retornaMenusESubmenus">
                    <ul class="dropdown-content" :id="'ddMenuTelaPequena_' + m.menu.id" v-if="m.submenus.length > 0">
                        <div v-for="(submenu, submenu_i) in m.submenus">
                            <li>
                                <a tabindex="0" :href="submenu.funcao_de_acesso_principal.rota">
                                    <i class="material-icons left"
                                       v-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'material'">{{submenu.icone}}</i>
                                    <i class="left"
                                       v-else-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'fontawesome'"
                                       :class="submenu.icone"
                                       v-else></i>
                                    {{submenu.titulo}}
                                </a>
                            </li>
                            <li class="divider"></li>
                        </div>
                    </ul>
                    <li v-if="m.submenus.length > 0">
                        <a tabindex="0" class="dropdown-trigger white-text" href="#!" :data-target="'ddMenuTelaPequena_' + m.menu.id">
                            <i class="material-icons left"
                               v-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                            <i class="left"
                               v-else-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'fontawesome'"
                               :class="m.menu.icone"
                               v-else></i>
                            {{m.menu.titulo}}
                            <i class="material-icons right">arrow_drop_down</i>
                        </a>
                    </li>

                    <li v-else>
                        <a tabindex="0" :href="m.menu.funcao_de_acesso_principal.rota" class="white-text">
                            <i class="material-icons left"
                               v-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                            <i class="left"
                               v-else-if="m.menu.icone != null && m.menu.icone != '' && m.menu.tipo_icone == 'fontawesome'"
                               :class="m.menu.icone"
                               v-else></i>
                            {{m.menu.titulo}}
                        </a>
                    </li>
                </div>

                <li>
                    <a tabindex="0" class="dropdown-trigger white-text" href="#!" data-target="ddLogin2">
                        {{usuarioLogado.name.split(' ')[0]}}&nbsp;&nbsp;<i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
                <ul id="ddLogin2" class="dropdown-content">
                    <li>
                        <a tabindex="0" href="/logout">Sair<i class="small material-icons">exit_to_app</i></a>
                    </li>
                </ul>

            </div>

        </ul>

        <vue-modal-login ref="modalFormLogin"></vue-modal-login>
    </div>
</template>

<script>

    export default {
        name: "menu",
        props: {
            usuarioLogadoJson: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                usuarioLogado: (this.usuarioLogadoJson == null || this.usuarioLogadoJson == '') ? null : JSON.parse(this.usuarioLogadoJson),
                menusESubmenus: null,
            }
        },
        computed: {
            retornaMenusESubmenus(){
                return this.menusESubmenus
            }
        },
        mounted() {
            this.montaMenusESubmenus()
        },
        methods: {
            montaMenusESubmenus() {
                this.menusESubmenus = this.retornaEstruturaDeMenusESubmenus(this.usuarioLogado)
            },
            login(){
                if(!this.$refs.modalFormLogin.abreJanelaFormLogin)
                    this.$refs.modalFormLogin.abreJanelaDeLogin()
            },
            fonte(e) {
                var elementos = $("p, h1, h2, h3, h4, h5, h6, span, li");
                _.forEach(elementos, (elemento) => {
                    var fonte = $(elemento).css('font-size');
                    var font_normal = $('body').css('font-size');

                    if (e === 'a') {
                        $(elemento).css("fontSize", parseInt(fonte) + 1);
                    }
                    if (e === 'd'){
                        $(elemento).css("fontSize", parseInt(fonte) - 1);
                    }
                    if (e === 'r') {
                        $(elemento).css("fontSize", parseInt(font_normal));
                        $('H1, H2, H3, H4, H5, H6').removeAttr('style');
                    }
                })
            },
        },
    }
</script>

<style scoped>

</style>
