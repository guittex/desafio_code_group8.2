<?php

namespace App\Http\Controllers;

use App\Models\Sorteios;
use App\Models\Jogadores;
use Illuminate\Http\Request;
use Session;

class SorteiosController extends Controller
{
    /**
     * Método inicial do sorteio.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $jogadores = Jogadores::all();
        $sorteios = $this->formatSorteios(Sorteios::all());
        
        return view('sorteios/index',[
            'jogadores' => $jogadores,
            'sorteios' => $sorteios
        ]);
    }

    private function formatSorteios($sorteios)
    {
        foreach ($sorteios as $key => $sorteio) {
            $sorteioFormatado[$sorteio->nome][] = $sorteio;
        }

        return $sorteioFormatado ?? [];
    }

    /**
     * Método que prepara os sorteios
     *
     * @param Request $request
     * @return void
    */
    public function sortear(Request $request)
    {
        $jogadores = $request->jogadores;

        $quantidadeTotalTime = $this->getQuantidadetTotalTime($jogadores, $request->numeroTotalJogadores);

        $validacaoGoleiros = $this->validarGoleiros($quantidadeTotalTime, $jogadores);

        if($validacaoGoleiros['status'] == false){
            return redirect('/sorteios')->with('msg-error', 'Está faltando ' . $validacaoGoleiros['quantidadeGoleiroFalta']  . ' goleiro(s) para o sorteio de times');

        }

        $goleiros = $this->getGoleiros($jogadores);

        $jogadoresSemGoleiros = $this->removerGoleiros($jogadores, $goleiros);

        $jogadoresSorteados = $this->sortearJogadores($jogadoresSemGoleiros, $goleiros, $request->numeroTotalJogadores, $quantidadeTotalTime);
        
        $nomeSorteio = $this->setNomeSorteio();

        foreach ($jogadoresSorteados as $key => $time) {
            $nomeTime = "Time " . ($key + 1);

            $this->saveSorteio($time, $nomeSorteio, $nomeTime);
        }

        return redirect()->action([SorteiosController::class, 'view'], ['nomeSorteio' => $nomeSorteio]);
    }

    public function view(Request $request)
    {
        $times = $this->formatTimes(Sorteios::where("nome", $request->nomeSorteio)->get());
        
        return view("sorteios/view",[
            'times' => $times,
            'nomeSorteio' => $request->nomeSorteio
        ]);
    }

    private function formatTimes($sorteios)
    {
        foreach ($sorteios as $key => $sorteio) {
            $sorteioFormatado[$sorteio->nome_time][] = $sorteio;
        }

        return $sorteioFormatado ?? [];
    }

    private function saveSorteio($time, $nomeSorteio, $nomeTime)
    {
        foreach ($time as $key => $jogador_id) {            
            $sorteio = new Sorteios();
        
            $sorteio->nome = $nomeSorteio;
            $sorteio->jogador_id = $jogador_id;
            $sorteio->nome_time = $nomeTime;

            $sorteio->save();
        }
    }

    private function setNomeSorteio()
    {
        $ultimoSorteio = Sorteios::latest()->first();

        if($ultimoSorteio != null){
            $nomeUltimoSorteio = str_replace("Sorteio ", "", $ultimoSorteio->nome);
            $novoNome = 'Sorteio ' . (intval($nomeUltimoSorteio) + 1);

        }else{
            $novoNome = "Sorteio 1";

        }

        return $novoNome;
    }

    /**
     * Método que sortea os jogadores
     *
     * @param array $jogadores
     * @param array $goleiros
     * @param int $numeroTotalJogadoresDefinidos
     * @param int $quantidadeTime
     * @return array
    */
    private function sortearJogadores($jogadores, $goleiros, $numeroTotalJogadoresDefinidos, $quantidadeTime)
    {
        shuffle($jogadores);
        shuffle($goleiros);

        $times = array_chunk($jogadores, $numeroTotalJogadoresDefinidos - 1);
        
        for ($i=0; $i < $quantidadeTime ; $i++) { 
            array_push($times[$i], $goleiros[$i]);
        }
        
        return $times;
    }
    
    /**
     * Método que remove os goleiros dos jogadores
     *
     * @param array $jogadores
     * @param array $goleiros
     * @return array
    */
    private function removerGoleiros($jogadores, $goleiros)
    {
        foreach ($goleiros as $key => $goleiro) {
            unset($jogadores[$key]);
        }
        
        return $jogadores;
    }

    /**
     * Método que pega os goleiros
     *
     * @param array $jogadores
     * @return array
    */
    private function getGoleiros($jogadores)
    {
        foreach ($jogadores as $key => $jogador) {
            if(strstr($jogador, 'goleiro')){
                $goleiros[$key] = str_replace(" goleiro", "", $jogador);

            }
        }

        return $goleiros;
    }

    /**
     * Método que valida a quantidade de goleiro necessária
     *
     * @param int $quantidadeTotalTime
     * @param array $jogadores
     * @return array
    */
    private function validarGoleiros($quantidadeTotalTime, $jogadores)
    {
        $qtdGoleiros = 0;

        foreach ($jogadores as $key => $jogador) {
            if(strstr($jogador, 'goleiro')){
                $qtdGoleiros++;

            }
        }

        if($quantidadeTotalTime == $qtdGoleiros){
            return [
                'status' => true
            ];

        }

        return [
            'status' => false,
            'quantidadeGoleiroFalta' => $quantidadeTotalTime - $qtdGoleiros
        ];
    }


    /**
     * Método que pega a quantidade total de time
     *
     * @param array $jogadores
     * @param int $numeroTotalJogadoresDefinidos
     * @return int
    */
    private function getQuantidadetTotalTime($jogadores, $numeroTotalJogadoresDefinidos)
    {
        $quantidadeNecessario = count($jogadores) / $numeroTotalJogadoresDefinidos;

        return intval(round($quantidadeNecessario, 1));
    }
}
