@extends('layouts.app')

@section('title', 'Computação Gráfica')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- CARD PÁGINA 1: CONCEITOS FUNDAMENTAIS --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h4 text-center dru-text-color-title">Desvendando os Conceitos Fundamentais – A Base de Tudo</h2>
            </div>
            <div class="card-body dru-text-fs-text">
                <p>Para entendermos a Computação Gráfica, precisamos começar pelo básico. Em sua essência, ela é a área da computação dedicada à geração, manipulação e exibição de imagens e modelos visuais utilizando o computador. Mas como isso realmente funciona?</p>

                <h3 class="h5 mt-3 dru-text-color-title">Pixels, Vetores e a Construção da Imagem</h3>
                <p>No nível mais fundamental, as imagens digitais podem ser representadas de duas maneiras principais: raster (ou bitmap) e vetorial.</p>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <h4 class="h6 mt-2 dru-text-color-subtitle">Imagens Raster</h4>
                        <p>Pense em uma imagem raster como um mosaico incrivelmente detalhado. Ela é composta por uma grade de pequenos quadrados coloridos chamados <strong>pixels</strong> (uma abreviação de "picture elements").</p>

                        {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_2.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                        <p>Cada pixel tem uma cor específica e, juntos, eles formam a imagem completa. Fotografias digitais e muitas imagens que vemos na web são exemplos de imagens raster. A qualidade de uma imagem raster está diretamente ligada à sua resolução, ou seja, ao número de pixels que ela contém. Quanto mais pixels, maior a riqueza de detalhes. No entanto, ao ampliar demais uma imagem raster, podemos começar a ver os pixels individuais, resultando em um efeito "quadriculado".</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <h4 class="h6 mt-2 dru-text-color-subtitle">Imagens Vetoriais</h4>
                        <p>Diferentemente das imagens raster, as imagens vetoriais não são baseadas em pixels, mas sim em fórmulas matemáticas que descrevem formas geométricas como linhas, curvas, círculos e polígonos. Imagine que, em vez de pintar cada pequeno quadrado de um mosaico, você está dando instruções precisas sobre como desenhar cada forma. "Desenhe uma linha reta daqui até ali com tal espessura e cor". A grande vantagem das imagens vetoriais é sua escalabilidade infinita. Como são baseadas em matemática, podem ser ampliadas ou reduzidas a qualquer tamanho sem perder qualidade ou ficarem pixelizadas. Logotipos, ilustrações e fontes tipográficas são frequentemente criados como gráficos vetoriais.</p>
                        
                        {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_3.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Cores e Modelos de Cor</h3>
                <p>A cor é um elemento crucial na Computação Gráfica. Para que os computadores possam "entender" e exibir cores, utilizamos modelos de cor. Os mais comuns são:</p>

                {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_4.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                <ul>
                    <li><strong>RGB (Red, Green, Blue):</strong> Este é um modelo aditivo, usado principalmente em telas (monitores, celulares). As cores são criadas pela combinação de diferentes intensidades de luz vermelha, verde e azul. A ausência de todas as cores resulta em preto, enquanto a combinação máxima de todas resulta em branco.</li>
                    <li><strong>CMYK (Cyan, Magenta, Yellow, Key/Black):</strong> Este é um modelo subtrativo, usado principalmente para impressão. As cores são criadas pela subtração de luz à medida que tintas ciano, magenta, amarela e preta são aplicadas ao papel branco.</li>
                    <li><strong>HSV/HSB (Hue, Saturation, Value/Brightness):</strong> Este modelo tenta representar as cores de uma maneira mais intuitiva para os humanos, descrevendo a tonalidade (a cor pura), a saturação (a intensidade da cor) e o valor/brilho (a claridade ou escuridão da cor).</li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body dru-text-fs-text">
                <h3 class="h5 mt-3 dru-text-color-title">Geometria e Modelagem 2D e 3D</h3>
                <p>A Computação Gráfica não se limita a imagens planas. Ela também nos permite criar e manipular objetos em duas e três dimensões.</p>
                <ul>
                    <li><strong>2D (Duas Dimensões):</strong> Envolve a criação de formas e imagens em um plano, como desenhos, ilustrações e interfaces gráficas. Utilizamos coordenadas (x, y) para definir a posição dos elementos.</li>
                    <li><strong>3D (Três Dimensões):</strong> Aqui a mágica realmente acontece! A modelagem 3D envolve a criação de representações matemáticas de objetos tridimensionais. Imagine esculpir digitalmente um personagem ou construir um carro virtual. Esses modelos são compostos por <strong>vértices</strong> (pontos no espaço 3D, definidos por coordenadas x, y, z), <strong>arestas</strong> (linhas que conectam os vértices) e <strong>faces</strong> (superfícies planas que formam o objeto, geralmente triângulos ou quadriláteros, chamados de <strong>polígonos</strong>). O conjunto de todos esses polígonos forma a <strong>malha poligonal (mesh)</strong> do objeto.</li>

                    {{-- LOCAL PARA INSERIR O GIF --}}
                        <div class="intro-gif-container">
                            <img src="{{ asset('img/marcacao_5.gif') }}" alt="GIF Ilustrativo sobre Computação Gráfica" class="intro-gif">
                        </div>
                        {{-- FIM DO LOCAL PARA INSERIR O GIF --}}

                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
