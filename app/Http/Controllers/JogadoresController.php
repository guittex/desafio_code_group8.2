<?php

namespace App\Http\Controllers;

use App\Models\Jogadores;
use Illuminate\Http\Request;

class JogadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jogadores = Jogadores::all();

        return view('jogadores/index',[
            'jogadores' => $jogadores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUpdate(Request $request)
    {
        if(!empty($request->id)){
            $jogador = Jogadores::findOrFail($request->id);

            $jogador->nome     = $request->nome;
            $jogador->nivel    = $request->nivel;
            $jogador->goleiro  = $request->goleiro;

            $msg = "Jogador editado com sucesso";
        }else{
            $jogador = new Jogadores();

            $jogador->nome     = $request->nome;
            $jogador->nivel    = $request->nivel;
            $jogador->goleiro  = $request->goleiro;

            $msg = "Jogador adicionado com sucesso";
        }

        try {
            $jogador->save();

            return redirect('/jogadores')->with('msg', $msg);

        } catch (\Throwable $th) {
            return redirect('/jogadores')->with('msg', 'Erro ao adicionar/editar jogador');

        }
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
     * @param  \App\Models\Jogador  $jogador
     * @return \Illuminate\Http\Response
     */
    public function show(Jogador $jogador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jogador  $jogador
     * @return \Illuminate\Http\Response
     */
    public function edit(Jogador $jogador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jogador  $jogador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jogador $jogador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jogador  $jogador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jogador $jogador)
    {
        //
    }
}
