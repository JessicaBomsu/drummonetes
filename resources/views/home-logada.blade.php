@extends('layouts.app')

@section('title', 'Conteúdo')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2>Index de Computação Gráfica</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <a href="{{route('content.introducao')}}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Conteúdo X</h5>
                        <small class="text-muted">Novo</small>
                    </div>
                    <p class="mb-1">Clique para acessar o conteúdo sobre X</p>
                    <small class="text-muted">Atualizado recentemente</small>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection