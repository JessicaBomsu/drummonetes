@extends('layouts.app')

@section('title', 'Redefinir Senha') {{-- Título da página atualizado --}}

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Redefinir Senha {{-- Título do card atualizado --}}
            </div>
            <div class="card-body">
                {{-- A action do formulário agora aponta para a rota que processa o reset final --}}
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    {{-- Campo oculto para o token de reset. O valor é passado pelo controller. --}}
                    {{-- Este token era o seu antigo "código", mas agora é gerenciado pelo PasswordBroker. --}}
                    <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label dru-text-color-labels">E-mail:</label>
                        {{-- O e-mail é passado pelo controller e pode ser readonly, pois já foi validado. --}}
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- A frase "Um código será enviado para o seu e-mail" foi removida,
                             pois este formulário é acessado após a verificação da palavra-chave secreta. --}}
                    </div>

                    {{-- O campo "Código" explícito foi removido. O token está no campo oculto acima. --}}
                    {{-- Se você ainda quiser mostrar um campo "Código" preenchido (não recomendado para tokens reais),
                         você poderia fazer <input type="text" name="token_display" value="{{ $token ?? '' }}" readonly>
                         mas o campo submetido deve ser o input hidden com name="token".
                         Para este fluxo simplificado, é melhor omitir o campo de código visível aqui. --}}

                    <div class="mb-3">
                        <label for="password" class="form-label dru-text-color-labels">Nova senha:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autofocus> {{-- Adicionado autofocus aqui --}}
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label dru-text-color-labels">Confirmar nova senha:</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                        {{-- Adicionado tratamento de erro para password_confirmation, embora o erro principal apareça no campo 'password' --}}
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary dru-text-color-btn">Redefinir senha</button>
                    </div>
                     <div class="text-center mt-3">
                        <a href="{{ route('login') }}">Voltar para o Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
