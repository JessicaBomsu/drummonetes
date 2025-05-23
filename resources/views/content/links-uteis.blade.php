@extends('layouts.app')

@section('title', 'Computação Gráfica')

@push('styles')
    <style>
        /* Seleciona os links dentro da lista que está na div com as classes específicas */
        .link {
            color: chocolate; /* Mude para a cor desejada */
            font-size: 12px !important; /* Mude para o tamanho desejado */
            /* Você pode adicionar outras propriedades, como negrito: */
            /* font-weight: bold; */
        }

        /* Opcional: Mudar a cor do link quando o mouse passa por cima (hover) */
        .link:hover {
            color: chocolate; /* Cor ao passar o mouse */
        }
    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- CARD LINKS ÚTEIS --}}
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="fs-2 text-center dru-text-color-title">Links de referência</h1>
            </div>
            <div class="card-body">
                <div class="w-100">
                    <div class="row fs-5">
                        <div class="col-12 col-md-12">
                            <p class="mb-2 dru-text-fs-text">Nosso compromisso é com a informação de qualidade. 
                                Por isso, as ideias e o conteúdo deste site são fruto de um trabalho de pesquisa e análise rigorosa. 
                                Este material foi desenvolvido com o auxílio das seguintes referências e publicações, que foram 
                                essenciais para a construção do conhecimento e a credibilidade do que apresentamos:
                            </p>
                        </div>
                        <div class="row fs-5 m-4">
                            <ul>
                                <li><a href="https://voitto.com.br/blog/artigo/o-que-e-computacao-grafica" class="link">https://voitto.com.br/blog/artigo/o-que-e-computacao-grafica</a></li>
                                <li><a href="https://mprove.de/visionreality/text/3.1.2_sketchpad.html" class="link">https://mprove.de/visionreality/text/3.1.2_sketchpad.html</a></li>
                                <li><a href="https://codecrush.com.br/blog/computacao-grafica-conceito"class="link">https://codecrush.com.br/blog/computacao-grafica-conceito</a></li>
                                <li><a href="https://ecdd.blog/guia-o-que-e-computacao-grafica/"class="link">https://ecdd.blog/guia-o-que-e-computacao-grafica/</a></li>
                                <li><a href="https://www.impacta.com.br/blog/como-esta-o-mercado-de-trabalho-para-a-computacao-grafica-hoje/"class="link">https://www.impacta.com.br/blog/como-esta-o-mercado-de-trabalho-para-a-computacao-grafica-hoje/</a></li>
                                <li><a href="https://www.cpet.com.br/tecnico-em-computacao-grafica#:~:text=Esse%20profissional%20deve%20possuir%20habilidades,educa%C3%A7%C3%A3o%20de%20%C3%A1udio%20e%20v%C3%ADdeo."class="link">https://www.cpet.com.br/tecnico-em-computacao-grafica#:~:text=Esse%20profissional%20deve%20possuir%20habilidades,educa%C3%A7%C3%A3o%20de%20%C3%A1udio%20e%20v%C3%ADdeo.</a></li>
                                <li><a href="https://medium.com/@bitsgrupo/computa%C3%A7%C3%A3o-gr%C3%A1fica-e-jogos-digitais-1e15f0febf7c"class="link">https://medium.com/@bitsgrupo/computa%C3%A7%C3%A3o-gr%C3%A1fica-e-jogos-digitais-1e15f0febf7c</a></li>
                                <li><a href="https://maxrender.com.br/artigo/6-dicas-para-quem-deseja-iniciar-na-computa%C3%A7%C3%A3o-gr%C3%A1fica"class="link">https://maxrender.com.br/artigo/6-dicas-para-quem-deseja-iniciar-na-computa%C3%A7%C3%A3o-gr%C3%A1fica</a></li>
                                <li><a href="https://www.terra.com.br/noticias/mercado-de-computacao-grafica-deve-chegar-a-usd-215-bilhoes-ate-2024-diz-estudo,407784a07d72d90248686f72159dd47bm06fjeuo.html#google_vignette"class="link">https://www.terra.com.br/noticias/mercado-de-computacao-grafica-deve-chegar-a-usd-215-bilhoes-ate-2024-diz-estudo,407784a07d72d90248686f72159dd47bm06fjeuo.html#google_vignette</a></li>
                                <li><a href="https://agenciabrasil.ebc.com.br/economia/noticia/2022-07/pandemia-faz-aumentar-profissionais-em-tecnologia-e-diminuir-em-midia"class="link">https://agenciabrasil.ebc.com.br/economia/noticia/2022-07/pandemia-faz-aumentar-profissionais-em-tecnologia-e-diminuir-em-midia</a></li>
                                <li><a href="https://www.researchgate.net/publication/372824360_OS_DESAFIOS_DO_ENSINO_DE_COMPUTACAO_GRAFICA_EM_MODALIDADE_REMOTA_EM_MEIO_A_PANDEMIA_DE_2020"class="link">https://www.researchgate.net/publication/372824360_OS_DESAFIOS_DO_ENSINO_DE_COMPUTACAO_GRAFICA_EM_MODALIDADE_REMOTA_EM_MEIO_A_PANDEMIA_DE_2020</a></li>
                                <li><a href="https://www.linguafiada.info/computacao-grafica-2/#google_vignette"class="link">https://www.linguafiada.info/computacao-grafica-2/#google_vignette</a></li>
                                <li><a href="https://g1.globo.com/tecnologia/noticia/2022/05/07/como-comecar-carreira-em-ti-vindo-de-outras-areas.ghtml"class="link">https://g1.globo.com/tecnologia/noticia/2022/05/07/como-comecar-carreira-em-ti-vindo-de-outras-areas.ghtml</a></li>
                                <li><a href="https://www.creativebloq.com"class="link">https://www.creativebloq.com</a></li>
                                <li><a href="https://www.artstation.com/?sort_by=community&dimension=all"class="link">https://www.artstation.com/?sort_by=community&dimension=all</a></li>
                                <li><a href="https://www.geeksforgeeks.org/computer-graphics-tutorials/"class="link">https://www.geeksforgeeks.org/computer-graphics-tutorials/</a></li>
                                <li><a href="https://www.tutorialspoint.com/computer_graphics/index.htm"class="link">https://www.tutorialspoint.com/computer_graphics/index.htm</a></li>
                                <li><a href="https://www.blender.org/"class="link">https://www.blender.org/</a></li>
                                <li><a href="https://www.autodesk.com/"class="link">https://www.autodesk.com/</a></li>
                                <li><a href="https://www.adobe.com/products/substance3d.html"class="link">https://www.adobe.com/products/substance3d.html</a></li>
                                <li><a href="https://www.khronos.org/"class="link">https://www.khronos.org/</a></li>
                                <li><a href="https://developer.nvidia.com/graphics-and-rendering"class="link">https://developer.nvidia.com/graphics-and-rendering</a></li>
                                <li><a href="https://gpuopen.com/"class="link">https://gpuopen.com/</a></li>
                                <li><a href="https://learn.unity.com/"class="link">https://learn.unity.com/</a></li>
                                <li><a href="https://dev.epicgames.com/community/unreal-engine/learning"class="link">https://dev.epicgames.com/community/unreal-engine/learning</a></li>
                                <li><a href="https://www.siggraph.org/"class="link">https://www.siggraph.org/</a></li>
                                <li><a href="https://dl.acm.org/"class="link">https://dl.acm.org/</a></li>
                                <li><a href="https://archive.org/"class="link">https://archive.org/</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
