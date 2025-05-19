@extends('layouts.app')

@section('title', 'Recuperar Senha - Passo 1')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Recuperar Senha
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.handle_email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label dru-text-color-labels">E-mail:</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <p class="dru-fs-x-small mt-1">Informe o e-mail da sua conta.</p>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary dru-text-color-btn">
                            Próximo
                        </button>
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
