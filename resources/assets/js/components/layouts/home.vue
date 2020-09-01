<template>
    <div>
        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <div class="col s12">
                            <a href="#!" class="breadcrumb" title="Página Inicial"><i class="material-icons">home</i></a>
                            <a href="#!" class="breadcrumb">Página Inicial</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="row">
                        <div class="col s12">
                            <div id="profile-card" class="card" style="overflow: visible;">
                                <div class="card-content indigo darken-3">
                                    <h5 class="thin white-text center">Painel do Usuário</h5>
                                </div>

                                <div class="card-content ">
                                    <ul class="collection-item avatar center">
                                        <li><i class="large material-icons circle">person</i></li>
                                    </ul>

                                    <h2 class="card-title activator grey-text text-darken-4 center">{{usuarioLogado.name}}</h2><br><br>
                                    <p><i class="material-icons">business</i> Empresa: {{usuarioLogado.funcionario.empresa == null ? 'N/a' : usuarioLogado.funcionario.empresa.nome_fantasia}}</p>
                                    <hr>
                                    <p><i class="material-icons">assignment_ind</i> Login: {{usuarioLogado.login == null || usuarioLogado.login == '' ? usuarioLogado.login_username : usuarioLogado.login}}</p>
                                    <hr>
                                    <p><i class="material-icons">security</i> Perfil: {{usuarioLogado.perfil.nome}}</p>
                                    <hr>
                                    <p><i class="material-icons">email</i> Email: {{usuarioLogado.email == null || usuarioLogado.email == '' ? 'N/a' : usuarioLogado.email}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row hide-on-med-and-down">
            <div class="col s12">
                <div class="container">
                    <div class="row">
                        <div class="col s12">
                            <div id="access-card" class="card" style="overflow: visible;">
                                <div class="card-content indigo darken-3">
                                    <h5 class="thin white-text center"><i class="small material-icons prefix">admin_panel_settings</i> Menu de Acesso</h5>
                                </div>

                                <div class="card-content">
                                    <div class="row">
                                        <div class="col xl6 l6 m6 s12"
                                             v-if="usuarioLogado.perfil.super_user == 1 || usuarioLogado.perfil.super_user == true">
                                            <div class="icon-block position-relative">
                                                <a href="/empresas" class="a-specific a"
                                                   @click="escondeTodasOpcoes"
                                                   @mouseover="mouseEmCima('CrudEmpresa')"
                                                   @mouseleave="mouseFora('CrudEmpresa')">
                                                    <h2 id="iconeCrudEmpresa"
                                                        class="center black-text"
                                                        style="margin-bottom: 0px;">
                                                        <i class="medium material-icons">business</i>
                                                    </h2>
                                                    <p id="tituloCrudEmpresa"
                                                       class="black-text center"
                                                       style="margin-top: 0px;">Empresa</p>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col xl6 l6 m6 s12" v-for="(m, menu_i) in menusESubmenus">
                                            <div class="icon-block position-relative"
                                                 v-if="m.submenus.length == 0">
                                                <a :href="m.menu.funcao_de_acesso_principal.rota"
                                                   class="a-specific a"
                                                   @click="escondeTodasOpcoes"
                                                   @mouseover="mouseEmCima('MenuId' + m.menu.id)"
                                                   @mouseleave="mouseFora('MenuId' + m.menu.id)">
                                                    <h2 :id="'iconeMenuId' + m.menu.id"
                                                        class="center black-text"
                                                        style="margin-bottom: 0px;">
                                                        <i class="medium material-icons"
                                                           v-if="m.menu.icone == null || m.menu.icone == ''">list</i>
                                                        <i class="medium material-icons"
                                                           v-else-if="m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                                                        <i class="medium"
                                                           :class="m.menu.icone"
                                                           v-else></i>
                                                    </h2>
                                                    <p :id="'tituloMenuId' + m.menu.id"
                                                       class="black-text center"
                                                       style="margin-top: 0px;">{{m.menu.titulo}}</p>
                                                </a>
                                            </div>

                                            <div class="icon-block position-relative"
                                                 v-else>
                                                <a class="a-specific a"
                                                   @click="mostraDiv('#opcoesMenuId' + m.menu.id, '#setaMenuId' + m.menu.id)"
                                                   @mouseover="mouseEmCima('MenuId' + m.menu.id)"
                                                   @mouseleave="mouseFora('MenuId' + m.menu.id)">
                                                    <h2 :id="'iconeMenuId' + m.menu.id"
                                                        class="center black-text"
                                                        style="margin-bottom: 0px;">
                                                        <i class="medium material-icons"
                                                           v-if="m.menu.icone == null || m.menu.icone == ''">list</i>
                                                        <i class="medium material-icons"
                                                           v-else-if="m.menu.tipo_icone == 'material'">{{m.menu.icone}}</i>
                                                        <i class="medium"
                                                           :class="m.menu.icone"
                                                           v-else></i>
                                                    </h2>
                                                    <p :id="'tituloMenuId' + m.menu.id"
                                                       class="black-text center"
                                                       style="margin-top: 0px;">{{m.menu.titulo}}
                                                    <i :id="'setaMenuId' + m.menu.id"
                                                       hierachy="setas"
                                                       class="tiny material-icons">expand_more</i></p>
                                                    <div :id="'opcoesMenuId' + m.menu.id"
                                                         hierachy="opcoes"
                                                         class="collection-specific collection center">
                                                        <a v-for="(submenu, submenu_id) in m.submenus"
                                                           :href="submenu.funcao_de_acesso_principal.rota"
                                                           class="collection-item-specific collection-item">
                                                            <i class="material-icons left"
                                                               v-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'material'">{{submenu.icone}}</i>
                                                            <i class="left"
                                                               v-else-if="submenu.icone != null && submenu.icone != '' && submenu.tipo_icone == 'fontawesome'"
                                                               :class="submenu.icone"
                                                               v-else></i>&nbsp;&nbsp;
                                                            {{submenu.titulo}}
                                                        </a>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "home",
        props: {
            usuarioLogadoJson: {
                type: String,
                default: null,
            },
        },
        data(){
            return {
                usuarioLogado: (this.usuarioLogadoJson == null || this.usuarioLogadoJson == '') ? null : JSON.parse(this.usuarioLogadoJson),
                menusESubmenus: null,
            }
        },
        mounted() {
            this.montaMenusESubmenusDeAcesso()
        },
        methods: {
            montaMenusESubmenusDeAcesso(){
                this.menusESubmenus = this.retornaEstruturaDeMenusESubmenus(this.usuarioLogado)
            },
            escondeTodasOpcoes () {
                $("div[hierachy='opcoes']").each(function () {
                    $(this).hide();
                });
                $("i[hierachy='setas']").each(function () {
                    $(this).html("expand_more");
                });
            },
            mostraDiv (divIdName, setaIdName) {
                if ($(divIdName).is(':visible')) {
                    this.escondeTodasOpcoes();
                    $(setaIdName).html("expand_more");
                } else {
                    this.escondeTodasOpcoes();
                    $(divIdName).show();
                    $(setaIdName).html("expand_less");
                }
            },
            mouseEmCima (nome) {
                $('#icone'.concat(nome)).removeClass("black-text");
                $('#icone'.concat(nome)).addClass("grey-text text-lighten-1");
                $('#titulo'.concat(nome)).removeClass("black-text");
                $('#titulo'.concat(nome)).addClass("grey-text text-lighten-1");
            },
            mouseFora (nome) {
                $('#icone'.concat(nome)).removeClass("grey-text text-lighten-1");
                $('#icone'.concat(nome)).addClass("black-text");
                $('#titulo'.concat(nome)).removeClass("grey-text text-lighten-1");
                $('#titulo'.concat(nome)).addClass("black-text");
            },
        }
    }
</script>

<style>
    .icon-block.position-relative {
        position: relative;
    }

    .a-specific.a {
        margin:0px;
        text-decoration:none;
        color: #FFFFFF;
        cursor: pointer;
    }

    .a-specific.a:hover {
        text-decoration:none;
    }

    .collection-specific.collection {
        margin:auto;
        max-width:100%;
        display:none;
        top: 10%;
        left: 0;
        right: 0;
    }

    .collection-item-specific.collection-item a{
        text-decoration: none;
    }

    .collection-item-specific.collection-item a:hover{
        text-decoration: none;
    }

    .collection-item-specific.collection-item a:active{
        text-decoration: none;
    }

    .collection-item-specific.collection-item a:focus{
        text-decoration: none;
    }
</style>
