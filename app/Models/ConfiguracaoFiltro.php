<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfiguracaoFiltro extends ModelComSoftDeletes
{
    protected $fillable = [
        'id',
        'equipe_id',
        'perfil_id',
        'usuario_id',
        'idade',
        'idade_max',
        'renda',
        'margem',
        'parcela',
        'qtd_par_totais',
        'qtd_par_rest',
        'somente_com_emprestimos',
        'somente_atualizados_confirme'
    ];

    protected $dates = ['created_at'];

    protected $appends = [
        'renda_formatada',
        'margem_formatada',
        'parcela_formatada',
        'para_quem_foi_aplicado_filtro',
        'data_cadastro_filtro',
        'filtro_idade',
        'ativo',
        'empresa',
        'total_fichas_filtradas',
    ];

    public function getAtivoAttribute(){
        return $this->attributes['deleted_at'] == null;
    }

    public function getFiltroIdadeAttribute(){
        $idade_min = $this->attributes['idade'] != null ? $this->attributes['idade'] : null;
        $idade_max = $this->attributes['idade_max'] != null ? $this->attributes['idade_max'] : null;

        if($idade_min != null && $idade_max != null)
            return "De $idade_min à $idade_max anos";
        elseif($idade_min != null)
            return "Acima $idade_min anos";
        elseif($idade_max != null)
            return "Até $idade_max anos";
        else
            return 'N/A';
    }

    public function getDataCadastroFiltroAttribute(){
        return date_format(date_create($this->attributes['created_at']), 'd/m/Y');
    }

    public function getRendaFormatadaAttribute()
    {
        if($this->attributes['renda'] != '' && $this->attributes['renda'] != 0.00 && $this->attributes['renda'] != null)
            return  'R$ '.number_format($this->attributes['renda'], 2, ',', '.');
        else
            return 'N/a';
    }

    public function getMargemFormatadaAttribute()
    {
        if($this->attributes['margem'] != '' && $this->attributes['margem'] != 0.00 && $this->attributes['margem'] != null)
            return  'R$ '.number_format($this->attributes['margem'], 2, ',', '.');
        else
            return 'N/a';
    }

    public function getParcelaFormatadaAttribute()
    {
        if($this->attributes['parcela'] != '' && $this->attributes['parcela'] != 0.00 && $this->attributes['parcela'] != null)
            return  'R$ '.number_format($this->attributes['parcela'], 2, ',', '.');
        else
            return 'N/a';
    }

    public function getParaQuemFoiAplicadoFiltroAttribute(){
        if($this->equipe != null)
            return ('(equipe) '.$this->equipe->nome);
        elseif ($this->perfil != null)
            return ('(perfil) '.$this->perfil->nome);
        else
            return ('(usuário) '.$this->usuario->name);
    }

    public function getEmpresaAttribute(){
        $empresa = $this->usuario != null ? $this->usuario->funcionario->empresa : $this->equipe->departamento->empresa;

        return $empresa;
    }

    /*
    public function getFiltrosCargosAttribute(){
        $cargosFiltros = 'N/A';

        foreach ($this->filtro_cargos()->get() as $cargo){
            if($cargosFiltros == 'N/A')
                $cargosFiltros = ($cargo->esfera != '' && $cargo->esfera != null ? $cargo->esfera.' - '.$cargo->cargo : $cargo->cargo);
            else
                $cargosFiltros = $cargosFiltros.', '.($cargo->esfera != '' && $cargo->esfera != null ? $cargo->esfera.' - '.$cargo->cargo : $cargo->cargo);
        }

        return $cargosFiltros;
    }

    public function getFiltrosCidadesAttribute(){
        $cidadesFiltros = 'N/A';

        foreach ($this->filtro_cidades()->get() as $cidade){
            if($cidadesFiltros == 'N/A')
                $cidadesFiltros = $cidade->cidade;
            else
                $cidadesFiltros = $cidadesFiltros.', '.$cidade->cidade;
        }

        return $cidadesFiltros;
    }

    public function getFiltrosSituacoesAttribute(){
        $situacoesFiltros = 'N/A';

        foreach ($this->filtro_situacoes()->get() as $situacao){
            if($situacoesFiltros == 'N/A')
                $situacoesFiltros = $situacao->situacao;
            else
                $situacoesFiltros = $situacoesFiltros.', '.$situacao->situacao;
        }

        return $situacoesFiltros;
    }

    public function getFiltrosBancosRecsAttribute(){
        $bancosRecsFiltros = 'N/A';

        foreach ($this->filtro_bancos_recs()->get() as $banco){
            if($bancosRecsFiltros == 'N/A')
                $bancosRecsFiltros = $banco->banco;
            else
                $bancosRecsFiltros = $bancosRecsFiltros.', '.$banco->banco;
        }

        return $bancosRecsFiltros;
    }

    public function getFiltrosBancosEmpsAttribute(){
        $bancosEmpFiltros = 'N/A';

        foreach ($this->filtro_bancos_emps()->get() as $banco){
            if($bancosEmpFiltros == 'N/A')
                $bancosEmpFiltros = $banco->banco;
            else
                $bancosEmpFiltros = $bancosEmpFiltros.', '.$banco->banco;
        }

        return $bancosEmpFiltros;
    }
    */

    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id')->withTrashed();
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id')->withTrashed();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id')->withTrashed();
    }

    public function filtro_cargos()
    {
        return $this->hasMany(FiltroCargo::class, 'config_id');
    }

    public function filtro_cidades()
    {
        return $this->hasMany(FiltroCidade::class, 'config_id');
    }

    public function filtro_situacoes()
    {
        return $this->hasMany(FiltroSituacao::class, 'config_id');
    }

    public function filtro_bancos_recs()
    {
        return $this->hasMany(FiltroBancoRec::class, 'config_id');
    }

    public function filtro_bancos_emps()
    {
        return $this->hasMany(FiltroBancoEmp::class, 'config_id');
    }

    // métodos

    private function geraScriptSqlParaFiltragemDasFichas($clientesParaIgnorar = null){
        try {
            $esferasParaFiltrar = $this->filtro_cargos()->count() > 0 ? '"'.implode('","', $this->filtro_cargos()->select('esfera')->distinct()->pluck('esfera')->toArray()).'"' : null;

            $cargosParaFiltrar = $this->filtro_cargos()->count() > 0 ? '"'.implode('","', $this->filtro_cargos()->select('cargo')->distinct()->pluck('cargo')->toArray()).'"' : null;

            $situacoesParaFiltrar = $this->filtro_cargos()->count() > 0 ? '"'.implode('","', $this->filtro_cargos()->select('situacao')->distinct()->pluck('situacao')->toArray()).'"' : null;

            $estadosParaFiltrar = $this->filtro_cidades()->count() > 0 ? implode(',', $this->filtro_cidades()->select('ufs_id')->distinct()->pluck('ufs_id')->toArray()) : null;

            $cidadesParaFiltrar = $this->filtro_cidades()->count() > 0 ? '"'.implode('","', $this->filtro_cidades()->select('cidade')->distinct()->pluck('cidade')->toArray()).'"' : null;
            //$situacoesParaFiltrar = count($this->filtro_situacoes) > 0 ? '"'.implode('","', $this->filtro_situacoes->pluck('situacao')->toArray()).'"' : null;

            $bancosRecParaFiltrar = $this->filtro_bancos_recs()->count() > 0 ? '"'.implode('","', $this->filtro_bancos_recs()->select('banco')->distinct()->pluck('banco')->toArray()).'"' : null;

            $bancosEmpParaFiltrar = $this->filtro_bancos_emps()->count() > 0 ? '"'.implode('","', $this->filtro_bancos_emps()->select('banco')->distinct()->pluck('banco')->toArray()).'"': null;

            $condicaoSql = '';

            if($clientesParaIgnorar != '' && $clientesParaIgnorar != null)
                $condicaoSql = 'clientes.id not in('.$clientesParaIgnorar.') ';

            if($this->attributes['idade'] != null || $this->attributes['idade_max'] != null){
                if($this->attributes['idade'] != null && $this->attributes['idade_max'] != null)
                    $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'(((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) >= '.$this->attributes['idade'].' or clientes.idade >= '.$this->attributes['idade']. ') and (TIMESTAMPDIFF(year,clientes.data_nascimento, now()) <= '.$this->attributes['idade_max'].' or clientes.idade <= '.$this->attributes['idade_max']. ')) or (clientes.data_nascimento is null and clientes.idade is null))' : '(((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) >= '.$this->attributes['idade'].' or clientes.idade >= '.$this->attributes['idade']. ') and (TIMESTAMPDIFF(year,clientes.data_nascimento, now()) <= '.$this->attributes['idade_max'].' or clientes.idade <= '.$this->attributes['idade_max']. ') or (clientes.data_nascimento is null and clientes.idade is null)) or (clientes.data_nascimento is null and clientes.idade is null))';
                elseif($this->attributes['idade'] != null)
                    $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) >= '.$this->attributes['idade'].' or clientes.idade >= '.$this->attributes['idade']. ') or (clientes.data_nascimento is null and clientes.idade is null))' : '((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) >= '.$this->attributes['idade'].' or clientes.idade >= '.$this->attributes['idade']. ') or (clientes.data_nascimento is null and clientes.idade is null))';
                elseif($this->attributes['idade_max'] != null)
                    $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) <= '.$this->attributes['idade_max'].' or clientes.idade <= '.$this->attributes['idade_max']. ') or (clientes.data_nascimento is null and clientes.idade is null))' : '((TIMESTAMPDIFF(year,clientes.data_nascimento, now()) <= '.$this->attributes['idade_max'].' or clientes.idade <= '.$this->attributes['idade_max']. ') or (clientes.data_nascimento is null and clientes.idade is null))';
            }

            if($this->attributes['renda'] != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'dados_bancarios_clientes.valor_bruto >= '.$this->attributes['renda'] : 'dados_bancarios_clientes.valor_bruto >= '.$this->attributes['renda'];
            if($this->attributes['margem'] != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'dados_bancarios_clientes.valor_margem >= '.$this->attributes['margem'] : 'dados_bancarios_clientes.valor_margem >= '.$this->attributes['margem'];
            if($this->attributes['parcela'] != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'emprestimo_clientes.valor_parcela >= '.$this->attributes['parcela'] : 'emprestimo_clientes.valor_parcela >= '.$this->attributes['parcela'];
            if($this->attributes['qtd_par_totais'] != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'emprestimo_clientes.quantidade_parcelas_total >= '.$this->attributes['qtd_par_totais'] : 'emprestimo_clientes.quantidade_parcelas_total >= '.$this->attributes['qtd_par_totais'];
            if($this->attributes['qtd_par_rest'] != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'emprestimo_clientes.quantidade_parcelas_restantes >= '.$this->attributes['qtd_par_rest'] : 'emprestimo_clientes.quantidade_parcelas_restantes >= '.$this->attributes['qtd_par_rest'];
            if($esferasParaFiltrar != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'cargo_clientes.esfera in('.$esferasParaFiltrar.')' : 'cargo_clientes.esfera in('.$esferasParaFiltrar.')';
            if($cargosParaFiltrar != null && strlen(str_replace($cargosParaFiltrar, '"', '')) > 0)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'cargo_clientes.nome in('.$cargosParaFiltrar.')' : 'cargo_clientes.nome in('.$cargosParaFiltrar.')';
            if($situacoesParaFiltrar != null && strlen(str_replace($situacoesParaFiltrar, '"', '')) > 0)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'cargo_clientes.situacao in('.$situacoesParaFiltrar.')' : 'cargo_clientes.situacao in('.$situacoesParaFiltrar.')';
            if($estadosParaFiltrar != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'endereco_clientes.ufs_id in('.$estadosParaFiltrar.')' : 'endereco_clientes.ufs_id in('.$estadosParaFiltrar.')';
            if($cidadesParaFiltrar != null && strlen(str_replace($cidadesParaFiltrar, '"', '')) > 0)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'endereco_clientes.cidade in('.$cidadesParaFiltrar.')' : 'endereco_clientes.cidade in('.$cidadesParaFiltrar.')';
            if($bancosRecParaFiltrar != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'dados_bancarios_clientes.banco in('.$bancosRecParaFiltrar.')' : 'dados_bancarios_clientes.banco in('.$bancosRecParaFiltrar.')';
            if($bancosEmpParaFiltrar != null)
                $condicaoSql = $condicaoSql != '' ? $condicaoSql.' and '.'emprestimo_clientes.banco in('.$bancosEmpParaFiltrar.')' : 'emprestimo_clientes.banco in('.$bancosEmpParaFiltrar.')';

            return $condicaoSql;
        }catch (\Exception $e){
            throw $e;
        }

    }

    private function geraBuilderParaFiltragemDeFichas($quantidadeDeFichas = null, $lockForUpdate = false){
        try {
            $consultaSql = DB::table('clientes')
                ->leftJoin('cargo_clientes', 'clientes.id', '=', 'cargo_clientes.clientes_id')
                ->leftJoin('endereco_clientes', 'clientes.id', '=', 'endereco_clientes.clientes_id')
                ->leftJoin('dados_bancarios_clientes', 'clientes.id', '=', 'dados_bancarios_clientes.clientes_id')
                ->leftJoin('emprestimo_clientes', 'clientes.id', '=', 'emprestimo_clientes.clientes_id');

            $consultaSql = $consultaSql->selectRaw('distinct clientes.id')->inRandomOrder();

            if($quantidadeDeFichas)
                $consultaSql = $consultaSql->limit($quantidadeDeFichas);
            if($lockForUpdate)
                $consultaSql = $consultaSql->lockForUpdate();

            return $consultaSql;
        }catch (\Exception $ex){
            throw $ex;
        }
    }

    public function getTotalFichasFiltradasAttribute(){
        try {
            $condicaoSqlFiltro = $this->geraScriptSqlParaFiltragemDasFichas();
            $consultaSqlFiltro = $this->geraBuilderParaFiltragemDeFichas();

            return $consultaSqlFiltro->whereRaw($condicaoSqlFiltro)->count();
        }catch (\Exception $ex){
            throw $ex;
        }

    }

    public function retornaFichasDoFiltroConfiguradoParaCarteiraDeUsuario($quantidadeDeFichas, $clientesParaIgnorar){
        try {
            $condicaoSqlFiltro = $this->geraScriptSqlParaFiltragemDasFichas($clientesParaIgnorar);
            $consultaSqlFiltro = $this->geraBuilderParaFiltragemDeFichas($quantidadeDeFichas, true);

            $consultaSqlFiltro = collect($consultaSqlFiltro->whereRaw($condicaoSqlFiltro)->get())->pluck('id');

            return $consultaSqlFiltro;
        }catch (\Exception $ex){
            throw $ex;
        }
    }


}
