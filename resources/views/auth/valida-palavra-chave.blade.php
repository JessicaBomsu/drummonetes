@extends('layouts.app')

@section('title', 'Recuperar Senha - Verificação')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Verificação de Segurança
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="mb-3">Para continuar a recuperação da conta associada ao e-mail: <strong>{{ $email ?? old('email', 'Não identificado') }}</strong>, por favor, insira sua palavra-chave secreta.</p>

                <form method="POST" action="{{ route('password.handle_keyword') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="palavra_chave_secreta" class="form-label dru-text-color-labels">Palavra-Chave Secreta:</label>
                        <input id="palavra_chave_secreta" type="password" class="form-control @error('palavra_chave_secreta') is-invalid @enderror" name="palavra_chave_secreta" required autofocus>
                        @error('palavra_chave_secreta')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary dru-text-color-btn">
                            Verificar e Continuar
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}">Tentar com outro e-mail</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
