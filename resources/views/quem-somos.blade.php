@extends('layouts.app')

@section('title', 'Quem Somos') {{-- Título da página atualizado --}}

@push('styles')
<style>
    .profile-card {
        text-align: center;
        margin-bottom: 2rem;
        position: relative; /* Para o nome flutuante */
    }

    .profile-image-container {
        position: relative;
        display: inline-block; /* Para que o link envolva apenas a imagem */
        overflow: hidden; /* Para o efeito de zoom não vazar se a imagem for maior que o contêiner */
        border: 4px solid #da7635; /* Borda no estilo dos cards */
        box-shadow: 4px 4px 0 #000; /* Sombra no estilo dos cards */
        background-color: #f8d27f; /* Cor de fundo similar aos cards para a imagem */
    }

    .profile-image-container img {
        display: block;
        width: 150px; /* Tamanho desejado para as imagens pixel art */
        height: 150px;
        object-fit: contain; /* Para garantir que a imagem caiba sem distorcer, caso não seja quadrada */
        transition: transform 0.3s ease-in-out; /* Efeito de zoom suave */
    }

    .profile-image-container:hover img {
        transform: scale(1.1); /* Efeito de zoom ao passar o mouse */
    }

    .profile-name {
        display: none; /* Oculto por padrão */
        position: absolute;
        bottom: 5px; /* Posição abaixo da imagem */
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7); /* Fundo semi-transparente */
        color: #f8e9a1; /* Cor do texto do nome */
        padding: 5px 10px;
        border-radius: 3px;
        font-family: '04B-balloon', monospace; /* Usando a fonte do botão para o nome */
        font-size: medium; /* Tamanho da fonte para o nome */
        white-space: nowrap; /* Para evitar que o nome quebre em duas linhas */
        z-index: 10;
    }

    .profile-image-container:hover .profile-name {
        display: block; /* Exibe o nome ao passar o mouse sobre o contêiner da imagem */
    }

    /* Ajuste para o título do card principal */
    .card-header h2.dru-text-color-title {
        color: chocolate; /* Mantendo a cor original se preferir */
        font-family: 'upheavtt', monospace; /* Mantendo a fonte original */
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                {{-- Aplicando a classe de título aqui também --}}
                <h2 class="dru-text-color-title text-center">Quem Somos</h2>
            </div>
            <div class="card-body">
                <div class="w-100 dru-text-fs-text mb-4"> {{-- Adicionado dru-text-fs-text para consistência e mb-4 --}}
                    <p class="mb-2">
                        Somos um grupo de entusiastas da tecnologia e apaixonadas pela computação gráfica,
                        composto por Barbara Coelho, Jaciendy Nunes Teixeira, Jessica Emely Bomsucesso e Victoria Fernanda Dionizio.
                    </p>
                    <p class="mb-2">
                        Desenvolvemos este site como um projeto para o Instituto de Tecnologia da Informação da Faculdade Carlos Drummond de Andrade, com o objetivo de apresentar a importância e as diversas aplicações da computação gráfica.
                    </p>
                    <p class="mb-2">
                        Nosso objetivo é proporcionar uma experiência interativa e educativa, onde os usuários podem aprender de forma divertida sobre os conceitos, história e o vasto campo da computação gráfica. Acreditamos no poder da computação gráfica para transformar dados em imagens visuais e seu impacto em áreas como jogos, design, cinema, medicina e muito mais.
                    </p>
                    <p class="mb-2">
                        Esperamos que este site desperte sua curiosidade e interesse por este campo fascinante!
                    </p>
                </div>

                {{-- Seção dos Perfis --}}
                <hr class="my-4">
                <h3 class="text-center dru-text-color-title mb-4">Nossa Equipe</h3>

                <div class="row justify-content-center">
                    @php
                        $equipe = [
                            ['nome' => 'Barbara', 'imagem' => 'Barbara.png', 'linkedin' => 'https://www.linkedin.com/in/barbara-exemplo'],
                            ['nome' => 'Jaciendy', 'imagem' => 'Jaciendy.png', 'linkedin' => 'https://www.linkedin.com/in/jaciendy-nunes-b483b2196/'],
                            ['nome' => 'Jéssica', 'imagem' => 'Jess.png', 'linkedin' => 'https://www.linkedin.com/in/jessica-bomsucesso'],
                            ['nome' => 'Victoria', 'imagem' => 'Vic.png', 'linkedin' => 'https://www.linkedin.com/in/victoria-fernanda-dionizio-aab960220'],
                        ];
                    @endphp

                    @foreach ($equipe as $membro)
                        <div class="col-md-3 col-sm-6 profile-card">
                            <a href="{{ $membro['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="profile-image-container">
                                <img src="{{ asset('img/' . $membro['imagem']) }}" alt="Personagem de {{ $membro['nome'] }}">
                                <span class="profile-name">{{ $membro['nome'] }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
