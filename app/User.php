<?php

namespace App;

use App\Models\CarteiraFichaCliente;
use App\Models\ConfiguracaoFiltro;
use App\Models\Funcionario;
use App\Models\Perfil;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Support\Facades\Crypt;
//use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'login',
        'active',
        'pendente_trocar_senha',
        'funcionarios_id',
        'perfil_id',
        'login_username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'super_user'
    ];

    public function getSuperUserAttribute(){
        return $this->perfil->super_user == 1;
    }

    public function perfil(){
        return $this->belongsTo(Perfil::class, 'perfil_id')->withTrashed();
    }

    public function funcionario(){
        return $this->belongsTo(Funcionario::class, 'funcionarios_id')->withTrashed();
    }

    public function carteiras(){
        return $this->hasMany(CarteiraFichaCliente::class, 'users_id');
    }

    public function configuracaoFiltros(){
        return $this->hasOne(ConfiguracaoFiltro::class, 'usuario_id', 'id');
    }

    public function bloqueia(){
        $this->data_bloqueio = date('Y-m-d H:i:s');
        $this->numero_bloqueios = $this->numero_bloqueios + 1;

        /*if($this->numero_bloqueios >= 3)
            $this->active = false;*/

        $this->save();
    }
}
