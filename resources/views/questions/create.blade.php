@extends('layouts.app')

@section('title', 'Criar Questão')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2>Criar Nova Questão para o Quiz</h2>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                
                <form action="{{ route('questions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Texto da Questão</label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3">{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="question_nivel" class="form-label">Nível</label>
                        <select class="form-control @error('question_nivel') is-invalid @enderror" id="question_nivel" name="question_nivel">
                            <option value="" {{ old('question_nivel') == '0' ? 'selected' : '' }}>Escolha uma opção</option>
                            <option value="1" {{ old('question_nivel') == '1' ? 'selected' : '' }}>Fácil</option>
                            <option value="2" {{ old('question_nivel') == '2' ? 'selected' : '' }}>Médio</option>
                            <option value="3" {{ old('question_nivel') == '3' ? 'selected' : '' }}>Difícil</option>
                        </select>
                        @error('question_nivel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 col-md-6 mb-3">
                        <label for="pontos" class="form-label">Pontos</label>
                        <input type="number" class="form-control @error('pontos') is-invalid @enderror" id="pontos" name="pontos" value="{{ old('pontos', 1) }}" min="1" max="10">
                        @error('pontos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>
                    
                    <h5 class="mt-4">Respostas</h5>
                    
                    @for($i = 0; $i < 4; $i++)
                    <div class="mb-3">
                        <label for="answers[{{ $i }}]" class="form-label">Resposta {{ $i + 1 }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('answers.'.$i) is-invalid @enderror" id="answers[{{ $i }}]" name="answers[]" value="{{ old('answers.'.$i) }}">
                            <div class="input-group-text">
                                <input class="form-check-input" type="radio" name="correct_answer" value="{{ $i }}" {{ old('correct_answer') == $i ? 'checked' : ($i == 0 ? 'checked' : '') }}>
                            </div>
                        </div>
                        @error('answers.'.$i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endfor
                    
                    @error('correct_answer')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Salvar Questão</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection