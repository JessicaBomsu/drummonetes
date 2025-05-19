@extends('layouts.app')

@section('title', 'Ranking')

@push('styles')
<style>
    .ranking-card-body {
        /* Herda a cor de texto do body ou defina uma cor escura padrão para o ranking */
        /* color: #1a103d; */ /* Exemplo de cor escura, ajuste se necessário */
        font-family: 'vhs-gothic', monospace; /* Fonte padrão para o conteúdo do ranking */
        padding: 1.5rem 1rem; /* Ajuste o padding do corpo do card do ranking */
    }

    .ranking-list-wrapper {
        max-width: 600px; /* Define uma largura máxima para a lista do ranking */
        margin: 0 auto; /* Centraliza a lista dentro do card-body */
        padding: 1rem;
        /* background-color: rgba(0,0,0,0.05); /* Opcional: um fundo sutil para a lista */
        /* border: 1px solid #da7635; */ /* Opcional: uma borda interna */
    }

    .ranking-header-row,
    .ranking-player-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0.5rem; /* Padding interno das linhas */
        align-items: center; /* Alinha verticalmente os itens */
    }

    .ranking-header-row {
        font-family: 'upheavtt', monospace; /* Fonte pixelada para os cabeçalhos NOME/PONTUACAO */
        color: #1a103d; /* Cor escura para os cabeçalhos */
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 2px solid #1a103d; /* Linha sólida abaixo dos cabeçalhos */
        margin-bottom: 0.5rem; /* Espaço abaixo da linha do cabeçalho */
        font-size: 1.4rem; /* Tamanho da fonte para os cabeçalhos */
    }

    .ranking-player-row {
        border-bottom: 1px dashed #5f4b8b; /* Linha tracejada entre os jogadores */
        font-size: 1rem; /* Tamanho da fonte para nome e pontuação */
        color: #1a103d; /* Cor escura para os dados dos jogadores */
    }

    .ranking-player-row:last-child {
        border-bottom: none; /* Remove a linha do último jogador */
    }

    .ranking-player-name {
        text-align: left;
        flex-grow: 1; /* Permite que o nome ocupe o espaço disponível */
    }

    .ranking-player-score {
        text-align: right;
        min-width: 80px; /* Largura mínima para alinhar as pontuações */
        font-weight: bold;
    }

    /* Garante que o título principal do ranking use a fonte correta */
    .card-header h1.dru-text-color-title {
        font-family: 'upheavtt', monospace !important;
        color: #1a103d !important; /* Cor escura para o título RANKING, como na imagem */
        font-size: 2.5rem; /* Tamanho maior para o título principal */
        letter-spacing: 2px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 col-md-9 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                {{-- Título do Ranking --}}
                <h1 class="dru-text-color-title text-center">RANKING</h1>
            </div>
            <div class="card-body ranking-card-body">
                <div class="ranking-list-wrapper">
                    {{-- Cabeçalho da Lista --}}
                    <div class="ranking-header-row">
                        <span class="ranking-player-name fs-4 dru-text-fs-text">NOME</span>
                        <span class="ranking-player-score fs-4 dru-text-fs-text">PONTUAÇÃO</span>
                    </div>

                    {{-- Corpo da Lista com os Jogadores --}}
                    <div class="ranking-body">
                        @if(isset($ranking) && count($ranking) > 0)
                            @foreach($ranking as $index => $jogador)
                            <div class="ranking-player-row">
                                <span class="ranking-player-name dru-text-fs-text">{{ $jogador['nome'] }}</span>
                                <span class="ranking-player-score dru-text-fs-text">{{ $jogador['pontuacao'] }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <p class="dru-text-fs-text" style="color: #1a103d;">Nenhuma pontuação registrada ainda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
