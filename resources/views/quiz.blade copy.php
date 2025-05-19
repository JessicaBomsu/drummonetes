@extends('layouts.app')

@section('title', 'Quiz')

@section('content')
    @php
        // use Illuminate\Support\Facades\Auth; // Não é necessário se já estiver no escopo do Blade
        $user = Auth::user(); // Auth::user() já está disponível globalmente nas views se o usuário estiver logado
    @endphp

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{-- Usando a classe de título do seu app.css se existir, ou um h2 padrão --}}
                    <h2 class="text-center dru-text-color-title mb-0">Quiz de Computação Gráfica</h2>
                </div>
                <div class="card-body" id="quiz-intro">
                    <p class="dru-text-fs-text">Olá, <strong>{{ $user->name ?? 'Visitante' }}</strong>! Este quiz contém {{ $questions->count() }} perguntas aleatórias sobre Computação Gráfica. Cada questão tem uma pontuação específica.</p>
                    <p class="dru-text-fs-text">Você deve escolher a resposta correta para cada pergunta. Ao finalizar, verá sua pontuação e poderá verificar o ranking geral.</p>
                    <div class="d-grid mt-4">
                        <button id="startQuizBtn" class="btn btn-success btn-lg dru-text-color-btn">Iniciar Quiz</button>
                    </div>
                </div>

                <div class="card-body" id="quiz-body" style="display: none;">
                    <div id="timer" class="text-end mb-3 fw-bold" style="display: none;">
                        Tempo: <span id="time">00:00</span><br>
                        Nível da Questão: <span id="nivel" class="ms-1 px-2 py-1 rounded"></span>
                    </div>

                    <form id="quizForm" action="{{ route('quiz.submit') }}" method="POST">
                        @csrf

                        @if(isset($questions) && $questions->count() > 0)
                            {{-- Usar values() para resetar as chaves do array/collection se necessário após shuffle --}}
                            @foreach ($questions->values() as $index => $question)
                                {{-- Campo oculto para enviar o ID de cada questão apresentada --}}
                                <input type="hidden" name="presented_question_ids[]" value="{{ $question->id }}">

                                {{-- Usar $loop->index para data-step e para o estilo display:none --}}
                                <div class="question-step mb-4" data-step="{{ $loop->index }}" data-nivel="{{ $question->level }}" style="{{ $loop->index > 0 ? 'display:none;' : '' }}">
                                    {{-- Usar $loop->iteration para o número da questão (começa em 1) --}}
                                    <h5 class="dru-text-color-labels">{{ $loop->iteration }}. {{ $question->question_text }}
                                        <small class="text-muted dru-fs-x-small">(Vale: {{ $question->points ?? 1 }} ponto{{ ($question->points ?? 1) > 1 ? 's' : '' }})</small>
                                    </h5>
                                    <div class="ms-4">
                                        @foreach($question->answers as $answer)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer-{{ $answer->id }}" value="{{ $answer->id }}" required>
                                            <label class="form-check-label dru-text-fs-text" for="answer-{{ $answer->id }}">
                                                {{ $answer->answer_text }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="button" class="btn btn-primary dru-text-color-btn btn-next">
                                            {{ $loop->last ? 'Enviar Respostas' : 'Próxima Pergunta' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="dru-text-fs-text">Nenhuma questão disponível no momento. Por favor, tente mais tarde.</p>
                            <div class="text-center mt-3">
                                <a href="{{ route('index') }}" class="btn btn-secondary">Voltar à Home</a>
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- O script JavaScript permanece o mesmo que você já tem --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const intro = document.getElementById("quiz-intro");
            const quizBody = document.getElementById("quiz-body");
            const steps = document.querySelectorAll(".question-step");
            const startButton = document.getElementById("startQuizBtn");
            const timerDisplay = document.getElementById("timer");
            const timeSpan = document.getElementById("time");
            const nivelSpan = document.getElementById("nivel");
            const quizForm = document.getElementById("quizForm");

            let currentStep = 0;
            let timerInterval;
            let seconds = 0;

            const nivelMap = {
                1: { label: "Fácil", class: "bg-success text-white" },
                2: { label: "Médio", class: "bg-warning text-dark" },
                3: { label: "Difícil", class: "bg-danger text-white" }
            };

            function startTimer() {
                if (timerInterval) clearInterval(timerInterval); // Limpa intervalo anterior se houver
                seconds = 0; // Reseta os segundos
                if (timerDisplay) timerDisplay.style.display = "block";
                timerInterval = setInterval(() => {
                    seconds++;
                    const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                    const secs = String(seconds % 60).padStart(2, '0');
                    if (timeSpan) timeSpan.textContent = `${mins}:${secs}`;
                }, 1000);
            }

            const updateNivelDisplay = () => {
                if (!steps[currentStep] || !nivelSpan) return;
                const nivel = steps[currentStep].getAttribute("data-nivel");
                const info = nivelMap[nivel] || { label: "N/A", class: "bg-secondary text-white" };
                nivelSpan.textContent = info.label;
                nivelSpan.className = `ms-1 px-2 py-1 rounded ${info.class}`;
            };

            if (startButton && intro && quizBody && steps.length > 0) {
                startButton.addEventListener("click", () => {
                    intro.style.display = "none";
                    quizBody.style.display = "block";
                    if (steps[0]) steps[0].style.display = "block"; // Garante que a primeira questão seja exibida
                    updateNivelDisplay();
                    startTimer();
                });

                document.querySelectorAll(".btn-next").forEach((button) => {
                    button.addEventListener("click", () => {
                        // Validar se uma opção foi marcada na questão atual antes de prosseguir
                        const currentQuestionRadios = steps[currentStep].querySelectorAll('input[type="radio"]');
                        let isAnswered = false;
                        currentQuestionRadios.forEach(radio => {
                            if (radio.checked) {
                                isAnswered = true;
                            }
                        });

                        if (!isAnswered && currentStep < steps.length -1) { // Não exige resposta para o último botão (que é submit)
                            alert("Por favor, selecione uma resposta antes de prosseguir.");
                            return;
                        }


                        if (currentStep < steps.length - 1) {
                            steps[currentStep].style.display = "none";
                            currentStep++;
                            steps[currentStep].style.display = "block";
                            updateNivelDisplay();
                        } else {
                            if (quizForm) quizForm.submit();
                        }
                    });
                });
            } else {
                if (intro && ! (steps.length > 0 && startButton) ) { // Se não há questões, mas a introdução existe
                    const noQuestionsMessage = document.createElement('p');
                    noQuestionsMessage.className = 'alert alert-warning dru-text-fs-text';
                    noQuestionsMessage.textContent = 'Nenhuma questão disponível no momento. Por favor, tente mais tarde.';
                    const startButtonContainer = startButton ? startButton.parentElement : null;
                    if (startButtonContainer) {
                        startButtonContainer.innerHTML = ''; // Limpa o botão de iniciar
                        startButtonContainer.appendChild(noQuestionsMessage);
                        const backButton = document.createElement('a');
                        backButton.href = "{{ route('index') }}"; // Rota da home
                        backButton.className = 'btn btn-secondary mt-3';
                        backButton.textContent = 'Voltar à Home';
                        startButtonContainer.appendChild(backButton);
                    }
                }
            }
        });
    </script>
@endsection
