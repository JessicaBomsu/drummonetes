@extends('layouts.app')

@section('title', 'Resultado do Quiz')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header text-white">
                <h2 class="mb-0">Resultado do Quiz</h2>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="fs-5">Sua Pontuação: {{ $qtdCorrects }}/{{ $totalQuestions }}</h3>
                    
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar {{ $percentage >= 70 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                             role="progressbar" style="width: {{ $percentage }}%" 
                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ round($percentage) }}%
                        </div>
                    </div>
                    
                    @if($percentage >= 70)
                        <div class="alert alert-success">
                            <h4 class="fs-6"><i class="fas fa-trophy"></i> Parabéns!</h4>
                            <p class="mb-0">Você demonstrou um excelente conhecimento em computação gráfica.</p>
                        </div>
                    @elseif($percentage >= 50)
                        <div class="alert alert-warning">
                            <h4 class="fs-6"><i class="fas fa-thumbs-up"></i> Bom trabalho!</h4>
                            <p class="mb-0">Seu conhecimento está bom, mas há espaço para melhoria.</p>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4 class="fs-6"><i class="fas fa-book"></i> Continue estudando!</h4>
                            <p class="mb-0">Recomendamos revisar o conteúdo e tentar novamente.</p>
                        </div>
                    @endif
                </div>
                
                <h4 class="fs-6 mb-3">Detalhes das Respostas:</h4>
                <div class="accordion" id="resultsAccordion">
                    @foreach($results as $index => $result)
                    {{-- @dd($result) --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $result['is_correct'] ? 'bg-light-success' : 'bg-light-danger' }} dru-fs-small" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" style="background-color: #CCDAFF">
                                Questão #{{ $index + 1 }}: 
                                <span class="{{ $result['is_correct'] ? 'text-success' : 'text-danger' }} ms-2">
                                    {{ $result['is_correct'] ? 'Correta' : 'Incorreta' }}
                                </span>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#resultsAccordion">
                            <div class="accordion-body">
                                <p>Nível: {{$result['questionLevel']}} | Pontuação: {{$result['points_earned']}} de {{$result['points_possible']}}</p>
                                <p><strong>Pergunta:</strong> {{ $result['question'] }}</p>
                                <p class="text-danger"><strong>Sua resposta:</strong> {{ $result['user_answer'] }}</p>
                                @if(!$result['is_correct'])
                                    <p class="text-success"><strong>Resposta correta:</strong> {{ $result['correct_answer'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4 gap-3">
                    @if($percentage >= 70)
                        <a href="{{ route('piano') }}" class="btn btn-secundary dru-text-color-btn-secundary dru-fs-small">
                            <i class="fas fa-book me-2"></i> Ver projeto - piano
                        </a>
                    @else
                        <a href="{{ route('content.introducao') }}" class="btn btn-secundary dru-text-color-btn-secundary dru-fs-small">
                            <i class="fas fa-book me-2"></i> Revisar estudo
                        </a>
                    @endif
                    <a href="{{ route('quiz') }}" class="btn btn-primary dru-text-color-btn dru-fs-small">
                        <i class="fas fa-redo me-2"></i> Tentar novamente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection