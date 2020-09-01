/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import MdTheme from "vue-material/src/core/MdTheme";

require('./bootstrap');

import Vue from 'vue'

//Componente de validação
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
//

//Componentes de Terceiros
import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask)

import money from 'v-money'
// register directive v-money and component <money>
Vue.use(money)

import ViaCep from 'vue-viacep'
Vue.use(ViaCep);

import LiquorTree from 'liquor-tree';
// global registration
Vue.use(LiquorTree)

//import noUiSlider from 'materialize-css/extras/noUiSlider';
import 'materialize-css/extras/noUiSlider/nouislider.css';


    //***Componentes Vue-Material***
import {
    MdEmptyState,
    MdIcon,
    MdButton,
    MdApp,
    MdList,
    MdToolbar,
    MdDrawer,
    MdContent,
} from 'vue-material/dist/components'
    Vue.use(MdEmptyState)
    Vue.use(MdIcon)
    Vue.use(MdButton)
    Vue.use(MdApp)
    Vue.use(MdList)
    Vue.use(MdToolbar)
    Vue.use(MdDrawer)
    Vue.use(MdContent)
    //***Componentes Vue-Material***

//Componentes de Terceiros

//estilos
import 'animate.css/animate.compat.css'
// estilos

//Plugins
import gerador_menu from "./components/plugins/gerador_menu";
Vue.use(gerador_menu, {someOption: true})
//Plugins

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Auth
Vue.component('vue-modal-login', require('./components/auth/form_login.vue').default);

//Layouts
Vue.component('vue-menu', require('./components/layouts/menu.vue').default);
Vue.component('vue-welcome', require('./components/layouts/welcome.vue').default);
Vue.component('vue-home', require('./components/layouts/home.vue').default);

//Utils
Vue.component('vue-alert', require('./components/utils/alert.vue').default);
Vue.component('vue-loading', require('./components/utils/gsi_loading.vue').default);
Vue.component('vue-paginate', require('./components/utils/paginate.vue').default);

//Configuração
Vue.component('vue-lista-empresas', require('./components/configuracao/empresas/lista_empresas.vue').default)
Vue.component('vue-cadastra-edita-empresas', require('./components/configuracao/empresas/cadastra_edita_empresas.vue').default)
Vue.component('vue-lista-departamentos', require('./components/configuracao/departamentos/lista_departamentos.vue').default)
Vue.component('vue-cadastra-edita-departamentos', require('./components/configuracao/departamentos/cadastra_edita_departamentos.vue').default)
Vue.component('vue-lista-equipes', require('./components/configuracao/equipes/lista_equipes.vue').default)
Vue.component('vue-cadastra-edita-equipes', require('./components/configuracao/equipes/cadastra_edita_equipes.vue').default)
Vue.component('vue-lista-cargos', require('./components/configuracao/cargos/lista_cargos.vue').default)
Vue.component('vue-cadastra-edita-cargos', require('./components/configuracao/cargos/cadastra_edita_cargos.vue').default)
Vue.component('vue-lista-funcionarios', require('./components/configuracao/funcionarios/lista_funcionarios.vue').default)
Vue.component('vue-cadastra-edita-funcionarios', require('./components/configuracao/funcionarios/cadastra_edita_funcionarios.vue').default)
Vue.component('vue-lista-perfis', require('./components/configuracao/perfis/lista_perfis.vue').default)
Vue.component('vue-cadastra-edita-perfis', require('./components/configuracao/perfis/cadastra_edita_perfis.vue').default)

//************Vendas************//
//Consultor
Vue.component('vue-painel-atendimento', require('./components/vendas/consultor/painel_atendimento.vue').default)
//Consultor -> Painel Componentes
Vue.component('vue-consultor-carteira', require('./components/vendas/consultor/painel_componentes/carteira.vue').default)
Vue.component('vue-consultor-busca-cpf', require('./components/vendas/consultor/painel_componentes/busca_cpf_cliente.vue').default)
Vue.component('vue-carteira-ficha', require('./components/vendas/consultor/painel_componentes/ficha.vue').default)
Vue.component('vue-carteira-ficha-detalhe', require('./components/vendas/consultor/painel_componentes/ficha_detalhe.vue').default)
//Consultor -> Painel Componentes -> Ficha Detalhe Componentes
Vue.component('vue-ficha-aba-pessoais', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_dados_pessoais.vue').default)
Vue.component('vue-ficha-aba-enderecos', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_enderecos.vue').default)
Vue.component('vue-ficha-aba-emprestimos', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_emprestimos.vue').default)
Vue.component('vue-ficha-aba-agendamentos', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_agendamentos.vue').default)
//Consultor -> Painel Componentes -> Ficha Detalhe Componentes -> Operações
Vue.component('vue-ficha-agendamento', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_operacoes/cadastra_edita_agendamento.vue').default)
Vue.component('vue-ficha-ignora-cliente', require('./components/vendas/consultor/painel_componentes/ficha_detalhe_componentes/ficha_detalhe_operacoes/ignora_cliente.vue').default)

//Gestor
//Gestor -> Filtro
Vue.component('vue-gestor-lista-filtros', require('./components/vendas/gestor/filtro/lista_filtros.vue').default)
Vue.component('vue-gestor-cadastra-filtros', require('./components/vendas/gestor/filtro/cadastra_filtro.vue').default)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app_menu = new Vue({
    el: '#app_menu',
});

const app = new Vue({
    el: '#app',
});
