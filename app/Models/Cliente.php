<?php

namespace App\Models;

use Carbon\Carbon;

class Cliente extends ModelComSoftDeletes
{
    protected $fillable = [
        'atualizado_confirmeonline'
    ];
    protected $dates = [
        'dt_ult_atu_confirme',
        'dt_ult_atu_portal'
    ];

    //Propriedades
    protected $appends = ['data_nascimento_br', 'idade', 'telefones_para_exibir', 'emails_para_exibir'];

    public function getDataNascimentoBrAttribute(){
        if($this->attributes['data_nascimento'] != null)
            return date_format(date_create($this->attributes['data_nascimento']), 'd/m/Y');
        else
            return null;
    }
    public function getIdadeAttribute(){
        if($this->attributes['data_nascimento'] != null)
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['data_nascimento'])->diffInYears(Carbon::now(), false);
        else
            return null;
    }

    //Métodos
    public function dados_bancarios(){
        return $this->hasMany(DadosBancariosCliente::class, 'clientes_id');
    }

    public function cargos(){
        return $this->hasMany(CargoCliente::class, 'clientes_id');
    }

    public function telefones(){
        return $this->hasMany(TelefoneCliente::class, 'clientes_id');
    }

    public function emails(){
        return $this->hasMany(EmailCliente::class, 'clientes_id');
    }

    public function enderecos(){
        return $this->hasMany(EnderecoCliente::class, 'clientes_id');
    }

    public function emprestimos(){
        return $this->hasMany(EmprestimoCliente::class, 'clientes_id');
    }

    public function fichas(){
        return $this->hasMany(FichaCliente::class, 'clientes_id')->withTrashed();
    }

    public function getTelefonesParaExibirAttribute(){
        $telefonesConfirme = $this->telefones()->where('atualizado_confirmeonline', '=',1)
            ->distinct()
            ->get(['numero', 'whatsapp', 'atualizado_confirmeonline']);

        $telefonesConfirme = $telefonesConfirme->count() > 0 ? $telefonesConfirme->toArray() : [];

        $telefonesPreCadastro = $this->telefones()->where('atualizado_confirmeonline', '=', 0)
            ->distinct()
            ->limit(3)
            ->get(['numero', 'whatsapp', 'atualizado_confirmeonline']);

        $telefonesPreCadastro = $telefonesPreCadastro->count() > 0 ? $telefonesPreCadastro->toArray() : [];

        $todoOsTelefones = array_merge($telefonesConfirme, $telefonesPreCadastro);

        if(count($todoOsTelefones) > 0)
            return $todoOsTelefones;
        else
            return [];

    }

    public function getEmailsParaExibirAttribute(){
        $emailsCredilink = $this->emails()
            ->where('atualizado_confirmeonline', '=', 1)
            ->get(['email', 'atualizado_confirmeonline']);

        $emailsCredilink = $emailsCredilink->count() > 0 ? $emailsCredilink->toArray() : [];

        $emailsSemCredilink = $this->emails()
            ->where('atualizado_confirmeonline', '=', 0)
            ->limit(3)
            ->get(['email', 'atualizado_confirmeonline']);

        $emailsSemCredilink = $emailsSemCredilink->count() > 0 ? $emailsSemCredilink->toArray() : [];

        $emails = array_merge($emailsCredilink, $emailsSemCredilink);

        if(count($emails) > 0)
            return $emails;
        else
            return [];
    }
}
