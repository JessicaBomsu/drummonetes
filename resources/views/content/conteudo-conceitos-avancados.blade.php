@extends('layouts.app')

@section('title', 'Computação Gráfica')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        
        {{-- CARD PÁGINA 3: CONCEITOS AVANÇADOS --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h4 text-center dru-text-color-title">Mergulhando em Conceitos Avançados – A Sofisticação por Trás da Magia</h2>
            </div>
            <div class="card-body dru-text-fs-text">
                <p>Agora que entendemos os fundamentos e a trajetória histórica, vamos explorar alguns dos conceitos mais avançados que tornam a Computação Gráfica tão poderosa e visualmente deslumbrante.</p>

                <h3 class="h5 mt-3 dru-text-color-title">Pipeline Gráfico (Graphics Pipeline)</h3>
                <p>Imagine uma linha de montagem em uma fábrica. O pipeline gráfico é um conceito semelhante: uma sequência de etapas que os dados gráficos (como os vértices de um modelo 3D) percorrem para serem transformados em pixels na tela.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_12.png') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif" style="max-width: 75%">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>Cada etapa realiza uma operação específica. As principais etapas incluem:</p>
                <ul>
                    <li><strong>Modelagem:</strong> Definição da geometria dos objetos.</li>
                    <li><strong>Transformações Geométricas:</strong> Operações como translação (mover), rotação (girar) e escala (redimensionar) os objetos no espaço 3D.</li>
                    <li><strong>Iluminação e Sombreamento (Shading):</strong> Cálculo de como a luz interage com as superfícies dos objetos para determinar suas cores e brilho. Diferentes modelos de sombreamento (como Phong, Gouraud, Blinn-Phong) produzem resultados variados de realismo e custo computacional.</li>
                    <li><strong>Projeção:</strong> Transformação das coordenadas 3D do mundo virtual para coordenadas 2D da tela do observador (como uma câmera virtual captura a cena).</li>
                    <li><strong>Recorte (Clipping):</strong> Remoção das partes dos objetos que estão fora do campo de visão da câmera.</li>
                    <li><strong>Rasterização:</strong> Conversão das informações geométricas (primitivas como triângulos) em pixels na tela.</li>
                    <li><strong>Texturização:</strong> Aplicação de imagens (texturas) às superfícies dos objetos para adicionar detalhes, como a madeira de uma mesa ou a pele de um personagem.</li>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_13.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <li><strong>Teste de Visibilidade/Profundidade (Z-buffering):</strong> Determinação de quais objetos ou partes de objetos estão na frente de outros, para que apenas as superfícies visíveis sejam desenhadas.</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Renderização (Rendering)</h3>
                <p>Renderização é o processo de gerar a imagem final a partir de um modelo 2D ou 3D usando programas de computador. Existem diferentes técnicas de renderização, cada uma com suas vantagens e desvantagens em termos de realismo e tempo de processamento:</p>
                <ul>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <li><strong>Renderização em Tempo Real (Real-time Rendering):</strong> Usada em jogos e aplicações interativas, onde as imagens precisam ser geradas muito rapidamente (geralmente 30 a 60 frames por segundo, ou mais). Prioriza a velocidade, muitas vezes sacrificando um pouco do realismo.</li>
                        </div>
                        <div class="col-12 col-md-6">
                            <li><strong>Renderização Offline (Offline Rendering):</strong> Usada em animações cinematográficas e efeitos visuais, onde a qualidade visual é a prioridade máxima e o tempo de renderização pode levar horas ou até dias por frame. Permite o uso de algoritmos mais complexos e custosos computacionalmente, como o ray tracing.</li>
                        </div>
                    </div>
                </ul>

                <h4 class="h6 mt-4 dru-text-color-subtitle">Ray Tracing (Traçado de Raios)</h4>
                <p>Como mencionado anteriormente, o ray tracing é uma técnica de renderização que simula o comportamento físico da luz. </p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_14.jpg') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>Para cada pixel na tela, um raio é traçado da câmera virtual para dentro da cena. O algoritmo então calcula as interações desse raio com os objetos (reflexões, refrações, sombras) para determinar a cor final do pixel. O resultado são imagens incrivelmente realistas, mas o processo é computacionalmente intensivo. Avanços recentes em hardware (como as GPUs RTX da Nvidia) tornaram o ray tracing em tempo real uma realidade em jogos.</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Animação por Computador</h3>
                <p>Dar vida a objetos e personagens é o domínio da animação por computador. Algumas técnicas incluem:</p>
                <ul>
                    <li><strong>Keyframing:</strong> O animador define poses-chave (keyframes) em diferentes momentos do tempo, e o computador interpola o movimento entre esses keyframes.</li>
                    <li><strong>Captura de Movimento (Motion Capture - MoCap):</strong> Sensores são colocados em um ator real, e seus movimentos são gravados e transferidos para um personagem digital.</p>
                    
                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_15.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    <p>Isso proporciona um movimento muito natural e realista.</li>
                    <li><strong>Simulação Física:</strong> Utilização de leis da física para animar fenômenos como tecidos, fluidos, fumaça, explosões e colisões de objetos.</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Modelagem Procedural</h3>
                <p>Em vez de criar cada detalhe manualmente, a modelagem procedural utiliza algoritmos para gerar geometria, texturas e outros elementos gráficos.</p>
                
                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_2.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <p>Isso é útil para criar mundos vastos e complexos (como florestas, cidades) ou objetos com padrões intrincados.</p>

                {{-- LOCAL PARA INSERIR O GIF --}}
                <div class="intro-gif-container">
                    <img src="{{ asset('img/marcacao_16.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                </div>
                {{-- FIM DO LOCAL PARA INSERIR O GIF --}}
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Shaders</h3>
                <p>Shaders são pequenos programas que rodam na GPU e controlam como os objetos são renderizados. Existem diferentes tipos de shaders:</p>
                <ul>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <li><strong>Vertex Shaders:</strong> Manipulam os atributos dos vértices (posição, cor, normais).</li>
                        </div>
                        <div class="col-12 col-md-4">
                            <li><strong>Pixel Shaders (ou Fragment Shaders):</strong> Calculam a cor de cada pixel individual.</li>
                        </div>
                        <div class="col-12 col-md-4">
                            <li><strong>Geometry Shaders:</strong> Podem criar nova geometria dinamicamente. Eles oferecem um controle incrivelmente fino sobre a aparência final da imagem.</li>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
