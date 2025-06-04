@extends('main')

@section('title', 'Профиль')

@section('content')

<div class="container d-flex justify-content-center align-items-center" style="padding-top: 230px; padding-bottom: 60px;">
    <div class="card bg-dark text-white"
        style="max-width: 380px; width: 100%; border-radius: 1rem; background: linear-gradient(135deg, #2c2f33, #23272a);">
        <div class="card-body p-4">

            <h4 class="mb-4 text-center fw-semibold text-white" style="letter-spacing: 1px; text-transform: uppercase;">
                Профиль пользователя
            </h4>

            @if(Auth::check() && Auth::user())
                <p class="mb-3 text-center fs-5">
                    <strong>Имя:</strong> <span class="text-light">{{ Auth::user()->name }}</span>
                </p>
                <p class="mb-3 text-center fs-5">
                    <strong>Логин:</strong> <span class="text-light">{{ Auth::user()->login }}</span>
                </p>
                <p class="mb-3 text-center fs-5">
                    <strong>Почта:</strong>
                    <a href="mailto:{{ Auth::user()->email }}" class="text-light text-decoration-underline">
                        {{ Auth::user()->email }}
                    </a>
                </p>
                <p class="mb-0 text-center fs-6 text-white-50">
                    <strong>Зарегистрирован:</strong><br>
                    {{ Auth::user()->created_at->format('d.m.Y H:i') }}
                </p>
            @else
                <p class="text-center text-warning fs-5">
                    Вы не авторизованы. <a href="{{ route('login') }}" class="text-info text-decoration-underline">Войти</a>
                </p>
            @endif

        </div>
    </div>
</div>

@endsection
