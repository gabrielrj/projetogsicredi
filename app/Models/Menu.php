<?php

namespace App\Models;

class Menu extends ModelComSoftDeletes
{
    protected $appends = ['submenus_ordenados', 'funcao_de_acesso_principal'];

    //Atributos
    public function getSubmenusOrdenadosAttribute(){
        return $this->submenus()->orderBy('ordem')->get();
    }

    public function getFuncaoDeAcessoPrincipalAttribute(){
        $funcaoDeAcessoPrincipal = $this->funcoesDeAcesso()->where('funcao', '=', 'index')->first();
        return $funcaoDeAcessoPrincipal;
    }

    //Métodos
    public function submenus(){
        return $this->hasMany(Submenu::class, 'menus_id', 'id');
    }

    public function funcoesDeAcesso(){
        return $this->hasMany(FuncaoAcesso::class, 'menus_id', 'id');
    }
}
