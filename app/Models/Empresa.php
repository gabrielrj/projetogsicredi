<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Empresa extends ModelComSoftDeletes
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = ['cpf_cnpj', 'razao_social', 'nome_fantasia', 'quantidade_licencas'];

    protected $appends = [
        'data_cadastro',
        'quantidade_usuarios_ativos',
        'quantidade_usuarios_inativos',
        'quantidade_total_usuarios',
        'ativo',
        'cargos',
        'equipes',
        'licencas_restantes',
    ];

    //Propriedades
    public function getAtivoAttribute(){
        return $this->attributes['deleted_at'] == null;
    }

    public function getQuantidadeUsuariosAtivosAttribute(){
        return $this->funcionarios()->withTrashed()
            ->whereNull('deleted_at')
            ->whereHas('usuario', function (Builder $usuario){
                $usuario->whereNull('deleted_at')
                    ->where('active', '=', 1);
            })
            ->get()
            ->count();
    }

    public function getQuantidadeUsuariosInativosAttribute(){
        return $this->funcionarios()->withTrashed()
            ->whereNotNull('deleted_at')
            ->whereHas('usuario', function (Builder $usuario){
                $usuario->whereNotNull('deleted_at')
                    ->where('active', '=', 0);
            })
            ->get()
            ->count();
    }

    public function getQuantidadeTotalUsuariosAttribute(){
        return $this->funcionarios()->get()->count();
    }

    public function getDataCadastroAttribute(){
        return $this->attributes['created_at'] == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format("d/m/Y");
    }

    public function getCargosAttribute(){
        $cargos = collect();

        if($this->departamentos()->count() > 0){
            foreach ($this->departamentos as $departamento){
                if($departamento->cargos()->count() > 0){
                    foreach ($departamento->cargos as $cargo)
                        $cargos->push($cargo);
                }
            }
        }

        return $cargos;
    }

    public function getEquipesAttribute(){
        $equipes = collect();

        if($this->departamentos()->count() > 0){
            foreach ($this->departamentos as $departamento){
                if($departamento->equipes()->count() > 0){
                    foreach ($departamento->equipes as $equipe)
                        $equipes->push($equipe);
                }
            }
        }

        return $equipes;
    }

    public function getLicencasRestantesAttribute(){
        $numeroDeLicencas = $this->attributes['quantidade_licencas'] != null ? $this->attributes['quantidade_licencas'] : 0;
        $numeroDeFuncionariosAtivos = $this->getQuantidadeUsuariosAtivosAttribute();

        return $numeroDeLicencas - $numeroDeFuncionariosAtivos;
    }

    //Métodos
    public function departamentos(){
        return $this->hasMany(Departamento::class)->withTrashed();
    }

    public function funcionarios(){
        return $this->hasMany(Funcionario::class)->withTrashed();
    }

    public function perfis(){
        return $this->hasMany(Perfil::class)->withTrashed();
    }
}
