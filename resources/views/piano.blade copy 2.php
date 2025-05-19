@extends('layouts.app')

{{-- Define o título da página, que será usado em layouts.app --}}
@section('title', 'Piano Virtual com Mãos')

{{-- Empurra estilos CSS específicos para esta página para o stack 'styles' em layouts.app --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/virtual_piano.css') }}">
@endpush

{{-- Conteúdo principal da página --}}
@section('content')
<div class="piano-container-wrapper"> {{-- Wrapper para padding e centralização geral --}}
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-11"> {{-- Ajustado para uma coluna um pouco mais larga se necessário --}}
            <div class="card">
                <div class="card-header">
                    {{-- Usando a classe de título do seu CSS --}}
                    <h2 class="text-center dru-text-color-title mb-0">Piano Virtual com Detecção de Mãos</h2>
                </div>
                <div class="card-body piano-card- p-0">
                    <div id="infoStatus" class="alert alert-info">Carregando recursos do piano...</div>
                    <video id="videoFeed" playsinline></video> {{-- playsinline pode ser útil para mobile --}}
                    <canvas id="pianoCanvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Empurra scripts JavaScript específicos para esta página para o stack 'scripts' em layouts.app --}}
@push('scripts')
    {{-- Scripts do MediaPipe --}}
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>

    {{-- Seus scripts customizados para o piano --}}
    <script src="{{ asset('js/piano_lists.js') }}"></script>
    <script src="{{ asset('js/virtual_piano.js') }}"></script>
@endpush
