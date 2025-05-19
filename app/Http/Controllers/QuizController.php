<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Ranking;

class QuizController extends Controller
{
    public function index()
    {
        // Busca questões por nível de dificuldade
        $easyQuestions = Question::with(['answers' => fn($query) => $query->inRandomOrder()])
            ->where('level', 1)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $mediumQuestions = Question::with(['answers' => fn($query) => $query->inRandomOrder()])
            ->where('level', 2)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $hardQuestions = Question::with(['answers' => fn($query) => $query->inRandomOrder()])
            ->where('level', 3)
            ->inRandomOrder()
            ->limit(2)
            ->get();

        // Junta todas as questões e embaralha a ordem final
        $questions = $easyQuestions
            ->concat($mediumQuestions)
            ->concat($hardQuestions)
            ->shuffle();

        if ($questions->isEmpty()) {
            return redirect()->route('content')->with('error', 'Não há questões disponíveis para o quiz. Por favor, volte mais tarde.');
        }

        return view('quiz', compact('questions'));
    }


    // public function submit(Request $request)
    // {
    //     // dd($request);
    //     $request->validate([
    //         'answers' => 'required|array',
    //         'answers.*' => 'required|exists:answers,id'
    //     ]);

    //     $score = 0;
    //     $totalQuestions = count($request->input('answers'));
    //     $results = [];
        
    //     foreach ($request->input('answers') as $questionId => $answerId) {
    //         $question = Question::with('answers')->find($questionId);
            
    //         if (!$question) {
    //             continue; // Pula se a questão não existir
    //         }
            
    //         $correctAnswer = $question->answers->firstWhere('is_correct', true);
            
    //         if (!$correctAnswer) {
    //             continue; // Pula se não houver resposta correta definida
    //         }
            
    //         $isCorrect = ($answerId == $correctAnswer->id);
            
    //         if ($isCorrect) {
    //             $score++;
    //         }
            
    //         // Armazena os resultados para exibição detalhada
    //         $results[] = [
    //             'question' => $question->question_text,
    //             'user_answer' => Answer::find($answerId)->answer_text,
    //             'correct_answer' => $correctAnswer->answer_text,
    //             'is_correct' => $isCorrect
    //         ];
    //     }
        
    //     // Calcula a porcentagem, evitando divisão por zero
    //     $percentage = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0;
        
    //     // Salva ou atualiza o ranking
    //     $user = Auth::user();
    //     // dd($user);
    //     Ranking::updateOrCreate(
    //         ['user_id' => $user->id],
    //         ['nome' => $user->name, 'pontuacao' => $percentage]
    //     );

    //     return view('quiz-result', [
    //         'score' => $score,
    //         'totalQuestions' => $totalQuestions,
    //         'percentage' => $percentage,
    //         'results' => $results
    //     ]);
    // }
    public function submit(Request $request)
    {
        $request->validate([
            'answers' => 'nullable|array', // Respostas podem não ser enviadas para todas
            'answers.*' => 'required|exists:answers,id', // Se uma resposta for enviada, ela deve existir
            'presented_question_ids' => 'required|array', // IDs das questões apresentadas
            'presented_question_ids.*' => 'required|exists:questions,id' // Cada ID deve existir
        ]);

        $userScore = 0;
        $maximumPossibleScore = 0;
        $results = [];
        $answeredQuestionIds = $request->input('answers', []); // Array de respostas do usuário (question_id => answer_id)
        $presentedQuestionIds = $request->input('presented_question_ids', []);

        // Carregar todas as questões apresentadas de uma vez para otimizar consultas
        $presentedQuestions = Question::with('answers')->whereIn('id', $presentedQuestionIds)->get()->keyBy('id');

        $qtdCorrects = 0;

        foreach ($presentedQuestionIds as $questionId) {
            $question = $presentedQuestions->get($questionId);

            if (!$question) {
                // Isso não deveria acontecer se a validação 'exists:questions,id' funcionar
                continue;
            }
            
            $questionLevelNum = $question->level;

            switch ($questionLevelNum) {
                case '2':
                    $questionLevel = 'Médio';
                    break;
                case '3':
                    $questionLevel = 'Difícil';
                    break;
                default:
                    $questionLevel = 'Fácil';
                    break;
            }

            // Adiciona os pontos da questão à pontuação máxima possível
            $questionPoints = $question->points ?? 1;
            $maximumPossibleScore += $questionPoints;

            $userAnswerId = $answeredQuestionIds[$questionId] ?? null; // Pega a resposta do usuário para esta questão
            $correctAnswer = $question->answers->firstWhere('is_correct', true);
            $userAnswerText = null;
            $isCorrect = false;

            if (!$correctAnswer) {
                // Se uma questão não tem resposta correta definida, não pode ser pontuada (erro de configuração)
                // Consideramos como errada para o usuário, mas logue isso para correção.
                \Log::error("Questão ID {$question->id} não possui resposta correta definida.");
                $userAnswerText = $userAnswerId ? (Answer::find($userAnswerId)->answer_text ?? 'Resposta inválida') : 'Não respondida';
            } else {
                if ($userAnswerId) {
                    $userAnswer = Answer::find($userAnswerId);
                    $userAnswerText = $userAnswer ? $userAnswer->answer_text : 'Resposta inválida';
                    if ($userAnswerId == $correctAnswer->id) {
                        $isCorrect = true;
                        $userScore += $questionPoints; // Adiciona os pontos da questão ao score
                        $qtdCorrects ++;
                    }
                } else {
                    // Usuário não respondeu a esta questão (pulou)
                    $userAnswerText = 'Não respondida';
                    // isCorrect permanece false, e 0 pontos são adicionados
                }
            }

            // Armazena os resultados para exibição detalhada
            $results[] = [
                'question' => $question->question_text,
                'user_answer' => $userAnswerText,
                'correct_answer' => $correctAnswer ? $correctAnswer->answer_text : 'N/A (sem resposta correta definida)',
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $questionPoints : 0,
                'points_possible' => $questionPoints,
                'questionLevel' => $questionLevel,
            ];
        }

        // Calcula a porcentagem com base na pontuação obtida e na pontuação máxima possível
        $percentage = $maximumPossibleScore > 0 ? ($userScore / $maximumPossibleScore) * 100 : 0;

        // Salva ou atualiza o ranking
        $user = Auth::user();
        if ($user) { // Garante que o usuário esteja logado
            Ranking::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nome' => $user->name,
                    // Decida se você quer salvar o score bruto ou o percentual no ranking.
                    // A imagem do seu ranking sugere pontuações brutas.
                    'pontuacao' => $percentage // Salvando o score bruto
                ]
            );
        }

        return view('quiz-result', [
            'score' => $userScore, // Pontuação total obtida
            'totalQuestions' => count($presentedQuestionIds), // Número total de questões apresentadas
            'maximumPossibleScore' => $maximumPossibleScore, // Pontuação máxima que poderia ser obtida
            'percentage' => round($percentage, 2), // Percentual de acerto arredondado
            'results' => $results, // Resultados detalhados por questão
            'qtdCorrects' => $qtdCorrects, // Soma de acertos
        ]);
    }
}