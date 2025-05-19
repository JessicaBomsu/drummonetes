@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label dru-text-color-labels">E-mail:</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label dru-text-color-labels">Senha:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <a href="{{ route('password.request') }}" class="form-check-label" for="reset-senha">Esqueci minha senha</a>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label dru-text-color-labels" for="remember">Lembrar-me</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary dru-text-color-btn">Login</button>
                    </div>
                    
                    <div class="mt-3 text-center">
                        Não tem uma conta? <a href="{{ route('register') }}">Registre-se</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection