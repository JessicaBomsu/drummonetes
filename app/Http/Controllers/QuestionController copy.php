<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;

class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:4',
            'answers.*' => 'required|string',
            'correct_answer' => 'required|integer|between:0,3'
        ]);
        
        $question = Question::create([
            'question_text' => $request->question_text,
            'level' => $request->question_nivel ?? '0',
            'points' => $request->pontos ?? '1'
        ]);

        foreach ($request->answers as $index => $answerText) {
            $answer = Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerText,
                'is_correct' => $index == $request->correct_answer
            ]);

            if ($index == $request->correct_answer) {
                $question->correct_answer_id = $answer->id;
                $question->save();
            }
        }

        return redirect()->route('questions.create')->with('success', 'Questão adicionada com sucesso!');
    }
}