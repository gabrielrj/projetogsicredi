export default {
    // 1. adicionar método ou propriedade global
    install(Vue, options){
        //console.log('Installing the CommentsOverlay plugin!')
        // Fun will happen here

        Vue.retornaEstruturaDeMenusESubmenus = function(usuarioLogado){
            let menusESubmenus = []

            if(usuarioLogado != null && usuarioLogado != '' && usuarioLogado != undefined){
                _.forEach(usuarioLogado.perfil.funcoes_de_acesso, (funcao) =>{
                    if((funcao.menu != null && funcao.menu != undefined) || (funcao.submenu != null && funcao.submenu != undefined)){
                        if(menusESubmenus.length == 0){

                            if(funcao.menu != null && funcao.menu != undefined) {
                                menusESubmenus.push({
                                    menu: funcao.menu,
                                    submenus: [],
                                })
                            }else{
                                menusESubmenus.push({
                                    menu: funcao.submenu.menu,
                                    submenus: [],
                                })

                                menusESubmenus[0].submenus.push(funcao.submenu)
                            }
                        }else{
                            if(funcao.menu != null && funcao.menu != undefined) {
                                menusESubmenus.push({
                                    menu: funcao.menu,
                                    submenus: [],
                                })
                            }else{
                                let indiceMenu = _.findIndex(menusESubmenus, (item) => {
                                    return item.menu.id == funcao.submenu.menu.id
                                })

                                if(indiceMenu >= 0){
                                    let indiceSub = _.findIndex(menusESubmenus[indiceMenu].submenus, (item) => {
                                        return item.funcao_de_acesso_principal.id == funcao.submenu.funcao_de_acesso_principal.id
                                    })

                                    if(indiceSub < 0){
                                        menusESubmenus[indiceMenu].submenus.push(funcao.submenu)
                                    }
                                }else if(indiceMenu < 0){
                                    menusESubmenus.push({
                                        menu: funcao.submenu.menu,
                                        submenus: [],
                                    })

                                    menusESubmenus[menusESubmenus.length - 1].submenus.push(funcao.submenu)
                                }


                            }
                        }
                    }
                })
            }

            return menusESubmenus

        }


        // 2. adicionar um recurso global
        Vue.directive('gerador_menu', {
            bind (el, binding, vnode, oldVnode) {
                // alguma lógica ...
            }

        })

        // 3. adicionar algumas opções de componente
        Vue.mixin({
            created: function () {
                // alguma lógica ...
            }

        })

        // 4. adicionar um método de instância
        Vue.prototype.retornaEstruturaDeMenusESubmenus = function(usuarioLogado){
            return Vue.retornaEstruturaDeMenusESubmenus(usuarioLogado)
        }

    }


}
