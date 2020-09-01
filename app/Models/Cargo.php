<?php

namespace App\Models;

class Cargo extends ModelComSoftDeletes
{
    protected $fillable = ['id', 'nome', 'departamentos_id'];
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

    public function departamento(){
        return $this->belongsTo(Departamento::class, 'departamentos_id')->withTrashed();
    }

    public function funcionarios(){
        return $this->hasMany(Funcionario::class);
    }
}
