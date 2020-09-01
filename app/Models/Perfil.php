<?php

namespace App\Models;

class Perfil extends ModelComSoftDeletes
{
    protected $fillable = ['id', 'nome'];
    protected $appends = ['data_cadastro', 'ativo', 'super_user'];

    // Atributos

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] != null ? date_format(date_create($this->attributes['created_at']), 'd/m/Y') : 'N/a';
    }

    public function getAtivoAttribute(){
        return $this->attributes['deleted_at'] == null;
    }

    public function getSuperUserAttribute(){
        return boolval($this->attributes['super_user']);
    }

    // Relacionamentos
    public function funcoesDeAcesso(){
        return $this->belongsToMany('App\Models\FuncaoAcesso', 'perfil_funcao_acesso', 'perfil_id', 'funcao_id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class)->withTrashed();
    }

    // Métodos
    public function configuracaoFiltros(){
        return $this->hasOne(ConfiguracaoFiltro::class, 'perfil_id');
    }

    public function possuiPermissaoDeAcesso($funcaoDeAcessoId){
        $resultado = $this->attributes['deleted_at'] == null ? $this->funcoesDeAcesso()->where('id', $funcaoDeAcessoId)->get() : null;
        return $resultado != null && $resultado->count() > 0;
    }
}
