<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendamentoFichaRequestValidation;
use App\Models\AgendamentoFichaCliente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendamentoFichaClienteController extends Controller
{

    private $fichaClienteController;

    public function __construct()
    {
        $this->fichaClienteController = new FichaClienteController();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgendamentoFichaRequestValidation $request)
    {
        DB::beginTransaction();

        try{
            $this->deletaAgendamentosAnteriores($request->ficha_id, 'Foi realizado um novo agendamento.');

            $novoAgendamento = new AgendamentoFichaCliente();
            $novoAgendamento->data_agendamento = Carbon::createFromFormat('d/m/Y', $request->data_agendamento)->format('Y-m-d');
            $novoAgendamento->hora_agendamento = Carbon::createFromFormat('H:i', $request->hora_agendamento)->format('H:i');
            $novoAgendamento->data_hora_agendamento = Carbon::createFromFormat('Y-m-d H:i', $novoAgendamento->data_agendamento.' '.$novoAgendamento->hora_agendamento)->format('Y-m-d H:i');

            $novoAgendamento->ficha_id = $request->ficha_id;
            $novoAgendamento->tipo_id = $request->tipo_id;
            $novoAgendamento->numero_telefone = $request->numero_telefone;
            $novoAgendamento->cep = $request->cep;
            $novoAgendamento->logradouro = $request->logradouro;
            $novoAgendamento->numero_endereco = $request->numero_endereco;
            $novoAgendamento->complemento = $request->complemento;
            $novoAgendamento->bairro = $request->bairro;
            $novoAgendamento->cidade = $request->cidade;
            $novoAgendamento->numero_telefone = $request->numero_telefone;
            $novoAgendamento->ufs_id = $request->ufs_id == 0 ? null : $request->ufs_id;
            $novoAgendamento->users_id = Auth::user()->getAuthIdentifier();
            $novoAgendamento->save();

            $statusFicha = $novoAgendamento->tipo_id == 1 ? 4 : 5;
            $fichaComStatusAlterado = FichaClienteController::alteraStatusFicha($novoAgendamento->ficha_id, $statusFicha, null);

            if(!$fichaComStatusAlterado)
                throw new \Exception('Desculpe, mas ocorreu um erro ao tentar alterar o status da ficha!');

            DB::commit();

            $agendamentosSalvos = AgendamentoFichaCliente::withTrashed()->with('tipo_agendamento', 'usuario.funcionario', 'uf')
                ->where('ficha_id', '=', $novoAgendamento->ficha_id)
                ->orderBy('id','desc')
                ->get();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Agendamento realizado com sucesso.',
                'agendamentosSalvos' => $agendamentosSalvos
            ], 201);
        }catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * @param $ficha_id
     * @param $motivo
     * @throws \Exception
     *
     * Método responsável por excluir todos os agendamentos anteriores de uma ficha no cadastro de um novo agendamento.
     *
     */
    public function deletaAgendamentosAnteriores($ficha_id, $motivo){
        $agendamentos = AgendamentoFichaCliente::where('ficha_id', $ficha_id)->get();

        foreach ($agendamentos as $agendamento){
            $this->deletaAgendamento($agendamento->id, $motivo);
        }
    }

    /**
     * @param $id
     * @param $motivo
     * @throws \Exception
     *
     * Método responsável por excluir um agendamento e salvar seu motivo.
     */
    public function deletaAgendamento($id, $motivo){
        try {
            $agendamento = AgendamentoFichaCliente::findOrFail($id);
            $agendamento->motivo_cancelamento = $motivo;
            $agendamento->save();
            $agendamento->delete();


            FichaClienteController::alteraStatusFicha($agendamento->ficha_id, 2);
        }catch (\Exception $e){
            throw $e;
        }

    }

}
