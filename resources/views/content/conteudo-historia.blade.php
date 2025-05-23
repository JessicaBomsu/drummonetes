@extends('layouts.app')

@section('title', 'Computação Gráfica')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        
        {{-- CARD PÁGINA 2: HISTÓRIA --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h4 text-center dru-text-color-title">Uma Viagem pela História – Dos Primeiros Rabiscos Digitais à Realidade Virtual</h2>
            </div>
            <div class="card-body dru-text-fs-text">
                <p>A Computação Gráfica não surgiu da noite para o dia. Sua história é uma fascinante cronologia de inovação, impulsionada pela curiosidade científica, necessidades militares e o desejo humano de criar e visualizar.</p>

                <h3 class="h5 mt-3 dru-text-color-title">Os Pioneiros e os Primeiros Passos (Décadas de 1950 e 1960)</h3>
                <p>Podemos traçar as origens da Computação Gráfica aos primeiros computadores e seus displays rudimentares. O Whirlwind I do MIT, no início dos anos 1950, já conseguia exibir gráficos simples em um tubo de raios catódicos (CRT).</p>
                <p>Um marco fundamental foi o <strong>Sketchpad</strong>, desenvolvido por Ivan Sutherland em 1963 no MIT.</p>

                {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_6.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>Considerado por muitos o "pai da Computação Gráfica", Sutherland criou um sistema revolucionário que permitia aos usuários desenhar diretamente na tela do computador usando uma caneta óptica. O Sketchpad introduziu conceitos como interfaces gráficas, restrições geométricas e hierarquia de objetos, que são fundamentais até hoje.</p>
                <p>Nessa época, a Computação Gráfica era cara, acessível apenas a grandes instituições de pesquisa e utilizada principalmente para aplicações científicas e militares, como simulações de voo e design auxiliado por computador (CAD).</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">A Era da Popularização e dos Jogos (Décadas de 1970 e 1980)</h3>
                <p>O desenvolvimento de hardware mais acessível e potente começou a democratizar a Computação Gráfica. Empresas como a Evans & Sutherland (fundada por Ivan Sutherland e David Evans) foram pioneiras em sistemas gráficos.</p>
                <p>A indústria de jogos eletrônicos emergiu como um grande motor de inovação. Jogos como "Pong" (1972), "Space Invaders" (1978) e "Pac-Man" (1980), embora visualmente simples para os padrões atuais, cativaram o público e impulsionaram o desenvolvimento de técnicas gráficas.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_7.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>O surgimento dos computadores pessoais (PCs) nos anos 80 levou a Computação Gráfica para dentro das casas e escritórios. Softwares de desenho e pintura começaram a aparecer.</p>
                <p>No cinema, filmes como <strong>"Tron"</strong> (1982), embora não tenha sido um sucesso estrondoso de bilheteria na época, foi um marco por seu uso extensivo de imagens geradas por computador (CGI), abrindo caminho para futuras produções.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_8.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">A Revolução 3D e a Internet (Décadas de 1990 e 2000)</h3>
                <p>A década de 1990 testemunhou uma explosão na Computação Gráfica 3D. O aumento do poder de processamento dos computadores e o desenvolvimento de algoritmos mais sofisticados permitiram a criação de mundos e personagens tridimensionais cada vez mais realistas.</p>
                <p>"Jurassic Park" (1993) revolucionou o cinema com seus dinossauros incrivelmente realistas criados digitalmente. <strong>"Toy Story" (1995)</strong> foi o primeiro longa-metragem totalmente animado por computador, marcando o início de uma nova era para a animação.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_9.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>Empresas como Pixar, Silicon Graphics (SGI) e Nvidia tornaram-se nomes proeminentes, impulsionando a inovação em hardware e software.</p>
                <p>A popularização da internet abriu novas fronteiras para a Computação Gráfica, com o desenvolvimento de gráficos para web, mundos virtuais e as primeiras experiências de realidade virtual.</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">A Era Moderna: Realismo, Interatividade e Inteligência Artificial (Década de 2010 – Presente)</h3>
                <p>Hoje, a Computação Gráfica atingiu níveis de realismo impressionantes, muitas vezes tornando difícil distinguir o real do virtual. Técnicas avançadas como <strong>ray tracing</strong> (traçado de raios), que simula o caminho da luz no ambiente virtual para criar reflexos, refrações e sombras ultrarrealistas, tornaram-se comuns em jogos e produções cinematográficas.</p>
                <p>A <strong>Realidade Virtual (VR)</strong> e a <strong>Realidade Aumentada (AR)</strong> estão transformando a maneira como interagimos com o conteúdo digital, oferecendo experiências imersivas em jogos, treinamento, educação e design.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_10.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>A Inteligência Artificial (IA) e o Machine Learning (Aprendizado de Máquina) estão sendo cada vez mais integrados à Computação Gráfica, automatizando tarefas complexas, gerando conteúdo de forma procedural e criando personagens mais inteligentes e responsivos.</p>
                <p><strong>Curiosidade:</strong> Você sabia que os algoritmos desenvolvidos para Computação Gráfica, especialmente para processamento paralelo em GPUs (Unidades de Processamento Gráfico), acabaram se mostrando incrivelmente úteis para treinar modelos de Inteligência Artificial? Essa sinergia inesperada impulsionou avanços em ambas as áreas!</p>
            </div>
        </div>
    </div>
</div>
@endsection
