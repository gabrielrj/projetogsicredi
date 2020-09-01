<?php

namespace App\Models;


class Submenu extends ModelComSoftDeletes
{
    protected $appends = ['funcao_de_acesso_principal'];

    public function getFuncaoDeAcessoPrincipalAttribute(){
        $funcaoDeAcessoPrincipal = $this->funcoesDeAcesso()->where('funcao', '=', 'index')->first();
        return $funcaoDeAcessoPrincipal;
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'menus_id');
    }

    public function funcoesDeAcesso(){
        return $this->hasMany(FuncaoAcesso::class, 'submenus_id', 'id');
    }
}
