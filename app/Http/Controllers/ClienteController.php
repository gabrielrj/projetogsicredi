<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use http\Client;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->listaClientes($id, null);
    }

    function buscaClienteCPF($cpf){
        try {
            $cliente = $this->listaClientes(null, $cpf);
            $cliente = $cliente != null ? $cliente->toArray() : [];

            if(count($cliente) == 0)
                throw new \Exception('O CPF informado não foi encontrado.');

            return response()->json([
                'status' => 'success',
                'cliente' => $cliente
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'erro',
                'msg'    => $e->getMessage(),
            ], 422);
        }

    }

    public function listaClientes($id = null, $cpf = null){
        $clientes = Cliente::with('cargos',
            'dadosBancarios',
            'emprestimos',
            'telefones',
            'emails',
            'enderecos.uf',
            'fichas.carteira',
            'fichas.carteira.usuario',
            'fichas.agendamentos',
            'fichas.agendamentos.tipo_agendamento',
            'fichas.agendamentos.uf',
            'fichas.historicoStatus.usuario',
            'fichas.historicoStatus.status');

        if($id != null)
            return $clientes->findOrFail($id);
        else
            return $clientes->where('cpf', '=', $cpf)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
