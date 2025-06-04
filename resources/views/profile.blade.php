@extends('main')
@section('title', 'Профиль')
@section('content')

@php
    $user = Auth::user();
@endphp

<div class="container d-flex justify-content-center align-items-center" style="padding-top: 100px; min-height: 60vh;">
    <div class="card p-4 shadow-lg bg-dark text-white" style="min-width: 300px; max-width: 500px; width: 100%; border-radius: 1rem;">
        <h2 class="text-center mb-4">Профиль пользователя</h2>

        @if($user)
            <p class="mb-3 text-center fs-5">
                <strong>Имя:</strong> <span class="text-light">{{ $user->name }}</span>
            </p>
            <p class="mb-3 text-center fs-5">
                <strong>Логин:</strong> <span class="text-light">{{ $user->login }}</span>
            </p>
            <p class="mb-3 text-center fs-5">
                <strong>Почта:</strong>
                <a href="mailto:{{ $user->email }}" class="text-light text-decoration-underline">
                    {{ $user->email }}
                </a>
            </p>
            <p class="mb-0 text-center fs-6 text-white-50">
                <strong>Зарегистрирован:</strong><br>
                {{ $user->created_at->format('d.m.Y H:i') }}
            </p>
        @else
            <p class="text-center text-warning fs-5">
                Вы не авторизованы. <a href="{{ route('login') }}" class="text-info text-decoration-underline">Войти</a>
            </p>
        @endif
    </div>
</div>

@endsection
