<?php

namespace App\Http\Controllers;

use App\Models\FichaCliente;
use App\Models\FuncaoAcesso;
use App\Models\HistoricoStatusFichaCliente;
use App\Models\Uf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FichaClienteController extends Controller
{
    private $historicoStatusFicha;

    public function __construct()
    {
        $this->middleware('permissao:'.FuncaoAcesso::where('rota', '/painel')->get()->implode('id'));

        $this->historicoStatusFicha = new HistoricoStatusFichaCliente();
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuarioLogado = Auth::user();

        $estados = Uf::all();

        $this->registraUltimoAcesso($id);

        $fichaDetalhe = FichaCliente::with('agendamentos',
            'agendamentos.tipo_agendamento',
            'agendamentos.uf',
            'cliente',
            'cliente.cargos',
            'cliente.dadosBancarios',
            'cliente.emprestimos',
            'cliente.telefones',
            'cliente.emails',
            'cliente.enderecos',
            'cliente.enderecos.uf',
            'historicoStatus',
            'historicoStatus.usuario',
            'historicoStatus.status',
            'cliente.fichas.carteira',
            'cliente.fichas.carteira.usuario',
            'cliente.fichas.agendamentos',
            'cliente.fichas.agendamentos.tipo_agendamento',
            'cliente.fichas.agendamentos.uf',
            'cliente.fichas.historicoStatus.usuario',
            'cliente.fichas.historicoStatus.status')->findOrFail($id);

        return view('painelconsultor.fichadetalhe', compact('estados', 'fichaDetalhe', 'usuarioLogado'));
    }



    public static function cadastraLoteDeFichas($carteiraId, $clientesNovosId){
        foreach ($clientesNovosId as $clienteId){
            $novaFicha = new FichaCliente();
            $novaFicha->cadastraNovaFicha($carteiraId, $clienteId, Auth::user()->getAuthIdentifier());
        }
    }

    public function registraUltimoAcesso($id){
        DB::beginTransaction();

        try{
            $ficha = FichaCliente::findOrFail($id);
            $ficha->ultimo_acesso = date('Y-m-d H:i:s');
            $ficha->save();

            if($ficha->status_atual->descricao == 'Disponível'){
                $this->historicoStatusFicha->insereNovoStatus($ficha->id, Auth::user()->getAuthIdentifier(), 2, null);
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            throw $e;
        }
    }

    public function ignoraCliente(Request $request){
        DB::beginTransaction();

        try{
            $ficha = FichaCliente::findOrFail($request->ficha_id);
            $motivoParaClienteSerIgnorado = $request->motivo;

            if($this->historicoStatusFicha->insereNovoStatus($ficha->id, Auth::user()->getAuthIdentifier(), 9, $motivoParaClienteSerIgnorado))
                $ficha->delete();

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 'erro',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }

    public static function alteraStatusFicha($ficha_id, $novo_status, $motivo_ignora_cliente = null){
        return (new HistoricoStatusFichaCliente())->insereNovoStatus($ficha_id, Auth::user()->getAuthIdentifier(), $novo_status, $motivo_ignora_cliente);
    }

    public static function excluiTodasAsFichasPorCarteiraNoStatusDeDisponivel($carteiraId){

        try {
            $fichas = FichaCliente::where('carteira_id', $carteiraId)
                ->whereHas('historicoStatus', function ($query1){
                    $query1->whereNull('deleted_at');
                    $query1->whereHas('status', function ($query2){
                        $query2->where('id', 1);
                    });
                })
                ->get();

            foreach ($fichas as $ficha){
                $ficha->delete();
            }

        }catch (\Exception $e){
            throw $e;
        }

    }

    public static function excluiTodasAsFichasPorCarteira($carteiraId){
        try {
            $fichas = FichaCliente::where('carteira_id', '=', $carteiraId)->get();

            foreach ($fichas as $ficha){
                $ficha->delete();
            }

        }catch (\Exception $e){
            throw $e;
        }
    }
}
