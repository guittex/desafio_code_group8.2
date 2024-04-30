@extends('adminlte::page')

@section('title', 'Jogadores')

@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h3>Sorteios</h3>
        </div>
        <div class="col-md-12">
            @if (session('msg'))      
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('msg-error'))      
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('msg-error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lista de Sorteios</h5>
                    </div>
                    <div class="col-md-6" style="text-align: right">
                        <button data-toggle="modal" data-target="#modal-sortear" class="btn btn-success">Sortear</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Data</th>
                                    <th>Quantidade jogadores</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sorteios as $key => $sorteio)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ date("d/m/Y H:i", strtotime($sorteio[0]->created_at)) }}</td>
                                        <td>{{ count($sorteio) }}</td>
                                        <td>
                                            <a href="/sorteios/view?nomeSorteio={{ $key }}" class="btn btn-primary btn-xs" >Ver Times</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-sortear" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('sorteio.sortear')}}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTitulo">
                                    Sortear Jogadores
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="divPasso1">
                                    <div class="col-md-6">
                                        <h5>Definir número de jogadores por time</h5>
                                        <input type="number" class="form-control" name="numeroTotalJogadores" onkeyup="habilitarPasso2(this.value)" placeholder="Digite..." id="numero-total-jogadores">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary" style="margin-top:32px" onclick="proximoPasso(1, 2)" id="btnProxPasso1" disabled>Próximo</button>
                                    </div>
                                </div>
                                <div class="row" id="divPasso2" style="display: none">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <label>Total de jogadores por time: <span id="spanTotalPorTime"> </span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Total confirmados: <span id="spanTotalSelecionados"> 0</span></label>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <h5>Selecione os jogadores confirmados</h5>
                                    </div>
                                    <div class="col-md-12" style="height: 280px;overflow:auto">
                                        @foreach ($jogadores as $jogador)
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="jogadores[]" 
                                                    id="jogador{{ $jogador->id }}" 
                                                    value="<?php  echo ($jogador->goleiro == true) ? $jogador->id . ' goleiro'   : $jogador->id ?>"
                                                >
                                                <label class="form-check-label">
                                                    {{ $jogador->nome }}
                                                    @if ($jogador->goleiro)
                                                        <b>- É goleiro</b>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                        <button type="button" class="btn btn-primary btn-block" onclick="proximoPasso(2, 1)">Voltar</button>
                                        <button type="submit" class="btn btn-success btn-block" id="btnSortear" disabled>Sortear</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                            </div>
                        </form>              
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/styles.css">
@stop

@section('js')
    <script>
        function habilitarPasso2(valor)
        {
            if(valor != ""){
                $("#btnProxPasso1").removeAttr("disabled");

                $("#spanTotalPorTime").html(valor);

            }else{
                $("#btnProxPasso1").attr("disabled", "disabled");

            }
        }

        function proximoPasso(passoAtual, proximoPasso)
        {
            $(`#divPasso${passoAtual}`).css("display", "none");

            $(`#divPasso${proximoPasso}`).fadeIn();
        }

        $(function (){
            $(".form-check-input").on("change", function(){
                var totalJogadoresSelecionados = $('input:checked').length;
                var totalJogadoresDefinidos = parseInt($("#spanTotalPorTime").html());

                $("#spanTotalSelecionados").html(totalJogadoresSelecionados);

                var totalJogadoresNecessarios = totalJogadoresDefinidos * 2;

                console.log(totalJogadoresDefinidos, totalJogadoresSelecionados, totalJogadoresNecessarios);

                if(parseInt(totalJogadoresSelecionados) >= totalJogadoresNecessarios){
                    $("#btnSortear").removeAttr("disabled");

                }else{
                    $("#btnSortear").attr("disabled", "disabled");
                }
            })
        })
    </script>
@stop