@extends('layouts.app')

@section('title', 'Computação Gráfica Completo')

@push('styles')
{{-- Adicione estilos específicos para o GIF se necessário --}}
<style>
    .intro-gif-container {
        text-align: center; /* Para centralizar o GIF se ele for display: block ou inline-block */
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .intro-gif {
        max-width: 100%; /* Garante que o GIF seja responsivo e não ultrapasse o contêiner */
        height: auto; /* Mantém a proporção do GIF */
        border: 3px solid #da7635; /* Opcional: uma borda no estilo do seu site */
        box-shadow: 5px 5px 0 #000000a0; /* Opcional: uma sombra */
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- CARD INTRODUÇÃO --}}
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="fs-2 text-center dru-text-color-title">Computação Gráfica: Da Magia Visual à Realidade Digital</h1>
            </div>
            <div class="card-body">
                <div class="w-100">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-4">
                            <h2 class="h5 dru-text-color-title">Introdução: Onde a Arte Encontra a Tecnologia</h2>
                            </div>
                    </div>
                    <div class="row fs-5">
                        <div class="col-12 col-md-12">
                            <p class="mb-2 dru-text-fs-text">Prepare-se para uma jornada fascinante pelo universo da Computação Gráfica!
                                Se você já se maravilhou com os efeitos especiais de um filme, se perdeu em mundos virtuais de
                                jogos eletrônicos ou utilizou um aplicativo com uma interface visualmente atraente, então você
                                já teve um encontro com essa área mágica que combina arte, ciência e muita tecnologia.
                            </p>
                            <p class="mb-2 dru-text-fs-text">
                                Neste texto, vamos desvendar os segredos da Computação Gráfica, desde seus conceitos mais
                                elementares até as aplicações mais avançadas que moldam nosso cotidiano e o futuro.
                                Exploraremos sua rica história, descobriremos curiosidades surpreendentes e entenderemos como
                                essa disciplina se tornou uma força motriz em diversas indústrias, abrindo um leque de
                                oportunidades para profissionais criativos e tecnicamente habilidosos.
                            </p>

                            {{-- LOCAL PARA INSERIR O GIF --}}
                            <div class="intro-gif-container">
                                <img src="{{ asset('img/marcacao_1.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                            </div>
                            {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                            <p class="mb-2 dru-text-fs-text">
                                Nossa jornada será interativa e didática, buscando apresentar as informações de forma clara e
                                envolvente, sem o peso de um jargão técnico excessivo.
                                O objetivo é que, ao final desta leitura, você tenha uma compreensão sólida sobre o que é a
                                Computação Gráfica, sua importância e o vasto potencial que ela carrega.
                                Então, ajuste seus óculos (virtuais ou reais) e vamos começar!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
