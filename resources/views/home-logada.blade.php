@extends('layouts.app')

@section('title', 'Drummonetes')

@push('styles')
    <style>
        
    </style>
@endpush

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- CARD LINKS ÚTEIS --}}
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="fs-2 text-center dru-text-color-title">Bem-vindo(a) à sua Área Exclusiva!</h1>
            </div>
            <div class="card-body">
                <div class="w-100">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-4">
                            <h2 class="h5 dru-text-color-title">Olá, <strong>{{ $user->name }}</strong>! É ótimo ter você conectado(a).</h2>
                        </div>
                    </div>
                    <div class="row fs-5">
                        <div class="col-12 col-md-12">
                            <p class="mb-2 dru-text-fs-text">
                                Explore nossos recursos interativos e aproveite sua experiência em nosso site sobre Computação Gráfica.
                            </p>
                        </div>
                    </div>
                    <div class="row fs-5 mt-5">
                        <div class="col-12 col-md-6 text-center">
                            <a href="{{ route('content.introducao') }}" class="btn btn-primary me-2 mb-2">Acessar Conteúdo</a>
                        </div>
                        <div class="col-12 col-md-6 text-center">
                            <a href="{{ route('piano') }}" class="btn btn-secundary dru-text-color-btn-secundary dru-fs-small">Ver Projeto CG: Piano</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
