@extends('adminlte::page')

@section('title', 'Jogadores')

@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h3>Jogadores</h3>
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
                        <h5>Lista de Jogadores</h5>
                    </div>
                    <div class="col-md-6" style="text-align: right;">
                        <button data-toggle="modal" data-target="#modal-adicionar" class="btn btn-success">Adicionar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" style="overflow: auto">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Nível</th>
                                    <th scope="col">É goleiro?</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jogadores as $jogador)
                                    <tr>
                                        <th scope="row">{{ $jogador->id }}</th>
                                        <td>{{ $jogador->nome }}</td>
                                        <td>{{ $jogador->nivel }}</td>
                                        <td>
                                            @if ($jogador->goleiro)
                                                Sim
                                            @else
                                                Não
                                            @endif
                                        </td>
                                        <td>
                                            <button 
                                                onclick="editarJogador(this)" 
                                                id="editButton{{ $jogador->id }}" 
                                                data-nome-jogador="{{ $jogador->nome }}" 
                                                data-id-jogador="{{ $jogador->id }}"
                                                data-nivel-jogador="{{ $jogador->nivel }}"
                                                data-goleiro-jogador="{{ $jogador->goleiro }}"
                                                class="btn btn-xs btn-warning">
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div>
            <div class="modal fade" id="modal-adicionar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('jogadores.add-edit')}}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTitulo">
                                    Adicionar Jogador
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="jogador-id">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Nome</label>
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite..." required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Nível</label>
                                        <input type="number" min="1" max="5" class="form-control" name="nivel" id="nivel" placeholder="Digite..." required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">É goleiro</label>
                                        <select class="form-control" name="goleiro" id="goleiro" placeholder="Digite..." required>
                                            <option value="">Selecione...</option>
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success" id="btnSaveJogador">Adicionar</button>
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
        function editarJogador(element)
        {
            let nomeJogador = $(element).data("nome-jogador");
            let idJogador = $(element).data("id-jogador");
            let nivelJogador = $(element).data("nivel-jogador");
            let goleiroJogador = $(element).data("goleiro-jogador");

            $("#nome").val(nomeJogador);
            $("#jogador-id").val(idJogador);
            $("#nivel").val(nivelJogador);
            $("#goleiro").val(goleiroJogador).trigger("change");

            $("#btnSaveJogador").html("Editar")
            $("#modalTitulo").html("Editar Jogador")

            $("#modal-adicionar").modal("show");
        }

        $(function (){
            $('#modal-adicionar').on('hidden.bs.modal', function () {
                $("#btnSaveJogador").html("Adicionar");
                $("#modalTitulo").html("Adicionar Jogador");
                $("#jogador-id").val("");
                $("#nome").val("");
                $("#nivel").val("");
                $("#goleiro").val("").trigger("change");
            });
        })
    </script>
@stop