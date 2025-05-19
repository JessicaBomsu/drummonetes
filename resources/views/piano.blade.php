@extends('layouts.app')

@section('title', 'Piano Virtual com Mãos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/virtual_piano.css') }}">
    <style>
        /* Estilos para a tela de introdução e para ocultar/mostrar seções */
        /* #piano-intro-screen, #piano-main-area {
            width: 100%; Garante que ocupem a largura da coluna do card
        } */

        #piano-main-area {
            display: none; /* Piano começa oculto */
        }

        #infoStatusIntro, #infoStatusPiano {
            margin-bottom: 1rem;
            text-align: center;
            width: 100%;
        }
        .intro-text {
            /* font-size: 0.95rem; */
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .privacy-note {
            /* font-size: 0.85rem; */
            color: #555;
            margin-top: 1.5rem;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        .btn-intro-action {
            margin-top: 0.5rem;
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('content')
<div class="piano-container-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-11">

            {{-- 1. Tela de Introdução/Consentimento (Visível inicialmente) --}}
            <div id="piano-intro-screen">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center dru-text-color-title mb-0">Bem-vindo ao Piano Virtual!</h2>
                    </div>
                    <div class="card-body">
                        <div id="infoStatusIntro" class="alert alert-info" role="alert">
                            Carregando recursos básicos...
                        </div>

                        <h5 class="dru-text-color-labels">Como Funciona:</h5>
                        <p class="intro-text">
                            Este piano virtual utiliza a câmera do seu dispositivo para detectar os movimentos das suas mãos.
                            Você poderá tocar as teclas do piano movendo seus dedos sobre elas na área de visualização da câmera.
                        </p>
                        <h5 class="dru-text-color-labels">Uso da Câmera e Privacidade:</h5>
                        <p class="intro-text">
                            Para que a mágica aconteça, precisamos de acesso à sua câmera.
                        </p>
                        <ul class="intro-text">
                            <li>As imagens da câmera são processadas em <strong>tempo real no seu navegador</strong>.</li>
                            <li><strong>Nenhuma imagem ou vídeo é gravado, armazenado ou enviado para nossos servidores</strong>. Sua privacidade é fundamental.</li>
                        </ul>
                        <p class="intro-text">
                            Ao clicar em "Concordo e Iniciar", você permitirá o acesso à sua câmera para esta sessão.
                        </p>

                        <div class="text-center mt-3">
                            <button id="agree-start-piano" class="btn btn-primary dru-text-color-btn dru-fs-small btn-lg btn-intro-action">Concordo e Iniciar!</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secundary dru-text-color-btn-secundary dru-fs-small btn-intro-action">Voltar</a>
                        </div>

                        <div class="privacy-note text-muted">
                            Este projeto foi desenvolvido para fins educacionais como parte do PEX 2025 da Faculdade Carlos Drummond de Andrade.
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Área Principal do Piano (Inicialmente oculta) --}}
            <div id="piano-main-area" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center dru-text-color-title mb-0">Piano Virtual</h2>
                    </div>
                    <div class="card-body piano-card-body p-0">
                        <div id="infoStatusPiano" class="alert alert-info">Carregando piano...</div>
                        <video id="videoFeed" playsinline></video>
                        <canvas id="pianoCanvas"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

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
