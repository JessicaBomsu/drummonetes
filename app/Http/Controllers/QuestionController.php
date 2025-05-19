<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Validation\Rule; // Importar a classe Rule

class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create'); // Certifique-se que esta view existe
    }

    public function store(Request $request)
    {
        // Validação dos dados da requisição
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:1000', // Aumentar max se necessário
            'question_nivel' => [
                'required',
                Rule::in(['1', '2', '3']), // Garante que o valor seja 1, 2 ou 3
                // Alternativamente, se '0' é o valor da opção "Escolha uma opção" e quer proibi-lo:
                // 'not_in:0',
            ],
            'pontos' => 'required|integer|min:1|max:10', // Exemplo: pontos entre 1 e 10
            'answers' => 'required|array|min:2|max:4', // Exemplo: mínimo 2, máximo 4 respostas
            'answers.*' => 'required|string|max:255', // Valida cada texto de resposta
            'correct_answer' => [ // O índice da resposta correta
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    // Validação customizada para garantir que o índice da resposta correta
                    // esteja dentro do range de respostas fornecidas.
                    // $value é o índice da resposta correta (0, 1, 2, 3...)
                    // $request->answers é o array de respostas
                    if (!is_array($request->answers) || !array_key_exists($value, $request->answers)) {
                        $fail('A resposta correta selecionada é inválida ou não corresponde a uma opção fornecida.');
                    }
                },
            ]
        ], [
            // Mensagens de erro personalizadas
            'question_text.required' => 'O texto da pergunta é obrigatório.',
            'question_nivel.required' => 'Por favor, selecione o nível da questão.',
            'question_nivel.in' => 'O nível selecionado é inválido. Escolha entre Fácil, Médio ou Difícil.',
            // 'question_nivel.not_in' => 'Por favor, selecione um nível válido para a questão (Fácil, Médio ou Difícil).',
            'pontos.required' => 'A pontuação da questão é obrigatória.',
            'pontos.integer' => 'A pontuação deve ser um número inteiro.',
            'pontos.min' => 'A pontuação deve ser de pelo menos :min ponto.',
            'pontos.max' => 'A pontuação não pode exceder :max pontos.',
            'answers.required' => 'É necessário fornecer as opções de resposta.',
            'answers.array' => 'As respostas devem ser fornecidas em um formato de lista.',
            'answers.min' => 'Forneça pelo menos :min opções de resposta.',
            'answers.max' => 'Forneça no máximo :max opções de resposta.',
            'answers.*.required' => 'O texto de cada opção de resposta é obrigatório.',
            'correct_answer.required' => 'Você deve indicar qual resposta é a correta.',
            'correct_answer.integer' => 'A indicação da resposta correta é inválida.',
        ]);

        // Se a validação falhar, o Laravel redireciona automaticamente com os erros.

        // Criação da Questão (após a validação passar)
        $question = Question::create([
            'question_text' => $validatedData['question_text'],
            // O nome do campo no formulário é 'question_nivel', mas no banco é 'level'
            'level' => $validatedData['question_nivel'],
            // O nome do campo no formulário é 'pontos', mas no banco é 'points'
            'points' => $validatedData['pontos']
        ]);

        $correctAnswerIdToStore = null;

        foreach ($validatedData['answers'] as $index => $answerText) {
            $answer = Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerText,
                // A validação já garante que 'correct_answer' é um índice válido
                'is_correct' => ($index == $validatedData['correct_answer'])
            ]);

            if ($index == $validatedData['correct_answer']) {
                $correctAnswerIdToStore = $answer->id;
            }
        }

        // Atualizar a questão com o ID da resposta correta, se aplicável
        if ($correctAnswerIdToStore) {
            $question->correct_answer_id = $correctAnswerIdToStore;
            $question->save();
        }

        return redirect()->route('questions.create')->with('success', 'Questão adicionada com sucesso!');
    }
}
