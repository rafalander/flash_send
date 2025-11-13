@extends('layouts.baseLogin')

@section('title', 'Login')

@section('header')
    <img src="{{ asset('images/icons/newlogodark.png') }}" alt="Flash Send Logo" style="max-width: 200px; height: auto; margin: 0 auto 1rem; display: block;">
@endsection

@section('content')
    <form action="#" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" required placeholder="Email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input id="password" name="password" type="password" class="form-control" required placeholder="Senha">
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input id="remember-me" name="remember-me" type="checkbox" class="form-check-input">
                <label for="remember-me" class="form-check-label">
                    Lembrar-me
                </label>
            </div>
            <div>
                <a href="#" class="text-decoration-none">
                    Esqueceu sua senha?
                </a>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary w-50 mx-auto">
                Entrar
            </button>
        </div>
    </form>
@endsection