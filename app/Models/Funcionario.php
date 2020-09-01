<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;

class Funcionario extends ModelComSoftDeletes
{
    protected $fillable = [
        'id',
        'rg',
        'nome',
        'cpf',
        'telefone_fixo',
        'telefone_celular',
        'matricula',
        'situacao_funcionarios_id',
        'departamentos_id',
        'cargos_id',
        'equipes_id',
        'empresa_id'
    ];

    protected $appends = ['data_cadastro', 'ativo', 'cpf_formatado'];

    // Atributos

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format("d/m/Y");
    }

    public function getAtivoAttribute(){
        return ($this->attributes['deleted_at'] == null && $this->usuario->deleted_at == null && ($this->usuario->active == true || $this->usuario->active == 1));
    }

    public function getCpfFormatadoAttribute(){
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "\$1.\$2.\$3-\$4", $this->cpf);
    }

    // Relacionamentos
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function usuario(){
        return $this->hasOne(User::class, 'funcionarios_id', 'id')->withTrashed();
    }

    public function situacao(){
        return $this->belongsTo(SituacaoFuncionario::class, 'situacao_funcionarios_id');
    }

    public function departamento(){
        return $this->belongsTo(Departamento::class, 'departamentos_id')->withTrashed();
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class, 'cargos_id')->withTrashed();
    }

    public function equipe(){
        return $this->belongsTo(Equipe::class, 'equipes_id')->withTrashed();
    }

}
