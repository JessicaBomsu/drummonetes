@extends('layouts.app')

@section('title', 'Computação Gráfica')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- CARD PÁGINA 4: APLICAÇÕES --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h4 text-center dru-text-color-title">Aplicações da Computação Gráfica – Onde a Mágica Acontece no Mundo Real</h2>
            </div>
            <div class="card-body dru-text-fs-text">
                <p>A influência da Computação Gráfica se estende por uma miríade de campos, transformando a maneira como trabalhamos, nos divertimos, aprendemos e interagimos com o mundo.</p>

                <h3 class="h5 mt-3 dru-text-color-title">Entretenimento</h3>
                <ul>
                    <li><strong>Jogos Eletrônicos:</strong> Provavelmente a aplicação mais visível e uma das maiores impulsionadoras da tecnologia gráfica. Desde simples jogos 2D até mundos 3D imersivos e fotorrealistas, a Computação Gráfica é o coração da experiência de jogo.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                    <div class="intro-gif-container">
                        <img src="{{ asset('img/marcacao_17.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                    </div>
                    {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Cinema e Televisão (Efeitos Visuais - VFX e Animação):</strong> Criaturas fantásticas, explosões espetaculares, cenários impossíveis e personagens animados cativantes são todos frutos da Computação Gráfica. Filmes como "Avatar", a saga "Vingadores" e animações da Pixar e DreamWorks demonstram o poder dessa tecnologia.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                    <div class="intro-gif-container">
                        <img src="{{ asset('img/marcacao_18.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                    </div>
                    {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Realidade Virtual (VR) e Realidade Aumentada (AR):</strong> Experiências imersivas em jogos, entretenimento interativo, tours virtuais e filtros de redes sociais.</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Design e Engenharia</h3>
                <ul>
                    <li><strong>Design Auxiliado por Computador (CAD - Computer-Aided Design):</strong> Arquitetos, engenheiros e designers industriais usam softwares CAD para criar modelos precisos de edifícios, carros, aviões, produtos e máquinas. Permite a visualização, simulação e otimização de projetos antes da fabricação.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_19.jpg') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Manufatura Auxiliada por Computador (CAM - Computer-Aided Manufacturing):</strong> Os modelos CAD são usados para controlar máquinas na fabricação de peças.</li>
                    <li><strong>Visualização de Produtos:</strong> Criação de imagens e animações realistas de produtos para marketing e vendas.</li>
                    <li><strong>Design de Interiores e Arquitetura:</strong> Criação de maquetes eletrônicas e renderizações fotorrealistas de espaços internos e externos.</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Ciência e Medicina</h3>
                <ul>
                    <li><strong>Visualização Científica:</strong> Representação gráfica de dados complexos para facilitar a compreensão de fenômenos científicos, como estruturas moleculares, fluxos de fluidos, dados meteorológicos e simulações astronômicas.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_20.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Imagens Médicas:</strong> Processamento e visualização de dados de exames como Tomografia Computadorizada (TC), Ressonância Magnética (RM) e Ultrassonografia, permitindo diagnósticos mais precisos e planejamento cirúrgico.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_21.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Simulações Cirúrgicas:</strong> Treinamento de cirurgiões em ambientes virtuais.</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Outras Aplicações</h3>
                <ul>
                    <li><strong>Educação e Treinamento:</strong> Softwares Educacionais Interativos, Simuladores.</li>
                    <li><strong>Publicidade e Marketing:</strong> Criação de anúncios visualmente atraentes, animações promocionais.</li>
                    <li><strong>Interfaces Gráficas do Usuário (GUI - Graphical User Interface):</strong> Praticamente todos os softwares e sistemas operacionais.</li>
                    <li><strong>Cartografia e Geoprocessamento:</strong> Criação de mapas digitais, sistemas de informação geográfica (SIG).</li>
                </ul>
                <p><strong>Curiosidade:</strong> A primeira sequência de CGI significativa em um filme de Hollywood foi a "Genesis Effect" em "Star Trek II: A Ira de Khan" (1982), criada pela Industrial Light & Magic (ILM), fundada por George Lucas. Embora "Tron" tenha sido lançado no mesmo ano com mais CGI, a sequência de Gênesis foi um marco técnico.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_22.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

            </div>
        </div>
    </div>
</div>
@endsection
