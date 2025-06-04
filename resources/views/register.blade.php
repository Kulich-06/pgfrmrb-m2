@extends('main')
@section('title', 'Регистрация')
@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="padding-top: 80px; padding-bottom: 60px;">
        <div class="card bg-dark text-white " style="max-width: 500px; width: 100%; border-radius: 1rem;">
            <div class="card-body p-4 p-sm-5">

                <h3 class="text-center mb-4 fw-bold text-uppercase">Регистрация</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" id="name" name="name" class="form-control bg-opacity-25 border-0 "
                            value="{{ old('name') }}" placeholder="Введите имя" required>
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" id="login" name="login" class="form-control  bg-opacity-25 border-0 "
                            value="{{ old('login') }}" placeholder="Введите логин" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control  bg-opacity-25 border-0 "
                            value="{{ old('email') }}" placeholder="example@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <input type="tel" id="phone" name="phone" class="form-control bg-opacity-25 border-0 "
                            value="{{ old('phone') }}" placeholder="+7..." required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" id="password" name="password" class="form-control bg-opacity-25 border-0 "
                            placeholder="Введите пароль" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-outline-light">Зарегистрироваться</button>
                    </div>
                </form>

                <p class="text-center text-white-50 mt-3 mb-0">
                    Уже есть аккаунт?
                    <a href="{{ route('login.form') }}" class="fw-bold text-white-50">Войти</a>
                </p>

            </div>
        </div>
    </div>
@endsection
