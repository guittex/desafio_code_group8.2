@extends('adminlte::page')

@section('title', 'Jogadores')

@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h3>{{ $nomeSorteio }}</h3>
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
                    <div class="col-md-12 float-r">
                        <a href={{ route("sorteios.index") }} class="btn btn-success">Novo Sorteio</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($times as $key => $times)
                        <div class="col-md-3">
                            <h4>
                                {{ $key }}
                            </h4>
                            <hr>
                            @foreach ($times as $time)
                                @if($time->jogador->goleiro)
                                    <p><b>Goleiro: </b> {{ $time->jogador->nome ?? null }}</p>
                                @else
                                    <p><b>Jogador: </b> {{ $time->jogador->nome ?? null }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
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
        

        $(function (){
            
        })
    </script>
@stop