@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Registrar Nova Conta
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label dru-text-color-labels">Nome:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus> {{-- Adicionado autocomplete="name" --}}
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label dru-text-color-labels">E-mail:</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"> {{-- Adicionado autocomplete="email" --}}
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label dru-text-color-labels">Senha:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password"> {{-- Adicionado autocomplete="new-password" --}}
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label dru-text-color-labels">Confirmar Senha:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"> {{-- Adicionado autocomplete="new-password" --}}
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="palavra_chave_secreta" class="form-label dru-text-color-labels">Palavra-Chave Secreta:</label>
                        <input type="password" class="form-control @error('palavra_chave_secreta') is-invalid @enderror" id="palavra_chave_secreta" name="palavra_chave_secreta" required>
                        <div id="palavraChaveHelp" class="form-text dru-fs-x-small">Esta palavra será usada se você esquecer sua senha. Guarde-a bem! Mínimo 2 caracteres.</div>
                        @error('palavra_chave_secreta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="palavra_chave_secreta_confirmation" class="form-label dru-text-color-labels">Confirmar Palavra-Chave Secreta:</label>
                        <input type="password" class="form-control" id="palavra_chave_secreta_confirmation" name="palavra_chave_secreta_confirmation" required>
                    </div>
                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary dru-text-color-btn">Criar conta</button>
                    </div>

                    <div class="mt-3 text-center">
                        Já tem uma conta? <a href="{{ route('login') }}">Faça login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
