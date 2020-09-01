<?php

namespace App\Models;

class FuncaoAcesso extends ModelComSoftDeletes
{
    protected $fillable = [
        'id',
        'titulo',
        'view',
        'controller',
        'rota',
        'nome_rota',
        'funcao',
        'menus_id',
        'submenus_id'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class, 'menus_id');
    }

    public function submenu(){
        return $this->belongsTo(Submenu::class, 'submenus_id');
    }
}
