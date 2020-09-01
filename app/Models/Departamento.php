<?php

namespace App\Models;

class Departamento extends ModelComSoftDeletes
{
    protected $fillable = ['id', 'nome', 'empresa_id'];
    protected $appends = ['data_cadastro', 'data_exclusao', 'ativo'];

    // Atributos

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? 'N/a' : date_format(date_create($this->attributes['created_at']), 'd/m/Y');
    }

    public function getDataExclusaoAttribute(){
        return $this->attributes['deleted_at'] == null ? 'N/a' : date_format(date_create($this->attributes['deleted_at']), 'd/m/Y');
    }

    public function getAtivoAttribute(){
        return $this->attributes['deleted_at'] == null;
    }

    // Métodos
    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id')->withTrashed();
    }

    public function equipes(){
        return $this->hasMany(Equipe::class, 'departamentos_id');
    }

    public function cargos(){
        return $this->hasMany(Cargo::class, 'departamentos_id');
    }

    public function funcionarios(){
        return $this->hasMany(Funcionario::class);
    }
}
