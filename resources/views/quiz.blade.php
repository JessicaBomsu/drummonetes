@extends('layouts.app')

@section('title', 'Quiz')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <!-- Tela inicial do quiz -->
                <div class="card-header">
                    <h2>Quiz de Computação Gráfica</h2>
                </div>
                <div class="card-body" id="quiz-intro">
                    <p>Olá, <strong>{{ $user->name }}</strong>! Este quiz contém 10 perguntas aleatórias sobre Computação Gráfica. Cada questão vale de 1 a 3 pontos.</p>
                    <p>Você deve escolher a resposta correta para cada pergunta. Ao finalizar, verá sua pontuação e poderá verificar o ranking geral.</p>
                    <div class="d-grid mt-4">
                        <button id="startQuizBtn" class="btn btn-success">Iniciar Quiz</button>
                    </div>
                </div>

                <!-- Formulário do quiz (inicialmente oculto) -->
                <div class="card-body" id="quiz-body" style="display: none;">
                    <div id="timer" class="text-end mb-3 fw-bold" style="display: none;">
                        Nível: <span id="nivel" class="ms-1 px-2 py-1 rounded"></span> | 
                        Tempo: <span id="time">00:00</span>
                    </div>                    

                    <form id="quizForm" action="{{ route('quiz.submit') }}" method="POST">
                        @csrf

                        @if(isset($questions) && $questions->count() > 0)
                            @foreach ($questions->values() as $index => $question)
                                {{-- Campo oculto para enviar o ID de cada questão apresentada --}}
                                <input type="hidden" name="presented_question_ids[]" value="{{ $question->id }}">
                                
                                <div class="question-step" data-step="{{ $loop->index }}" data-nivel="{{ $question->level }}" style="{{ $loop->index > 0 ? 'display:none;' : '' }}">
                                    {{-- Usar $loop->iteration para o número da questão (começa em 1) --}}
                                    <h5 class="dru-text-color-labels">{{ $loop->iteration }}. {{ $question->question_text }}
                                        <small class="text-muted dru-fs-x-small">(Vale: {{ $question->points ?? 1 }} ponto{{ ($question->points ?? 1) > 1 ? 's' : '' }})</small>
                                    </h5>
                                    {{-- <h5>{{ $index + 1 }}. {{ $question->question_text }}</h5> --}}
                                    <div class="ms-4">
                                        @foreach($question->answers as $answer)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer-{{ $answer->id }}" value="{{ $answer->id }}" required>
                                            <label class="form-check-label" for="answer-{{ $answer->id }}">
                                                {{ $answer->answer_text }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="button" class="btn btn-primary btn-next">
                                            {{ $loop->last ? 'Enviar Respostas' : 'Próxima' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Nenhuma questão disponível no momento.</p>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const intro = document.getElementById("quiz-intro");
            const quizBody = document.getElementById("quiz-body");
            const steps = document.querySelectorAll(".question-step");
            let currentStep = 0;

            document.getElementById("startQuizBtn").addEventListener("click", () => {
                intro.style.display = "none";
                quizBody.style.display = "block";
                document.getElementById("timer").style.display = "block";
                startTimer();
            });

            // document.querySelectorAll(".btn-next").forEach((button, index) => {
            //     button.addEventListener("click", () => {
            //         if (index < steps.length - 1) {
            //             steps[currentStep].style.display = "none";
            //             steps[++currentStep].style.display = "block";
            //         } else {
            //             document.getElementById("quizForm").submit();
            //         }
            //     });
            // });

            let timerInterval;
            let seconds = 0;

            function startTimer() {
                timerInterval = setInterval(() => {
                    seconds++;
                    const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                    const secs = String(seconds % 60).padStart(2, '0');
                    document.getElementById("time").textContent = `${mins}:${secs}`;
                }, 1000);
            }

            const nivelMap = {
                1: { label: "Fácil", class: "bg-success text-white" },
                2: { label: "Médio", class: "bg-warning text-dark" },
                3: { label: "Difícil", class: "bg-danger text-white" }
            };

            const nivelSpan = document.getElementById("nivel");

            const updateNivel = () => {
                const step = steps[currentStep];
                const nivel = step.getAttribute("data-nivel");

                const info = nivelMap[nivel] || { label: "N/A", class: "bg-secondary text-white" };

                nivelSpan.textContent = info.label;

                // Limpa classes anteriores e adiciona as novas
                nivelSpan.className = `ms-1 px-2 py-1 rounded ${info.class}`;
            };

            updateNivel();

            document.querySelectorAll(".btn-next").forEach((button, index) => {
                button.addEventListener("click", () => {
                    if (index < steps.length - 1) {
                        steps[currentStep].style.display = "none";
                        steps[++currentStep].style.display = "block";
                        updateNivel();
                    } else {
                        document.getElementById("quizForm").submit();
                    }
                });
            });
        });
    </script>
@endsection
