@extends('main')
@section('title', 'Главная страница')
@section('content')
    <div class="container">
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-1 mt-md-1 pb-1">

                                    <h2 class="fw-bold mb-2 text-uppercase">Вход</h2>
                                    <p class="text-white-50 mb-5"></p>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{ route('login.store') }}" method="post">
                                        @csrf

                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <input type="login" name="login" class="form-control form-control-lg"
                                                placeholder="Логин" value="{{ old('login') }}" />
                                            <label class="form-label" for="name"></label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                placeholder="Пароль" value="{{ old('password') }}" />
                                            <label class="form-label" for="password"></label>
                                        </div>
                                        <button data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-outline-light btn-lg px-5" type="submit">Войти</button>
                                    </form>
                                </div>
                                <br>
                                <div>
                                    <p class="mb-0">Нет аккаунта? <a href="{{ route('register.form') }}"
                                            class="text-white-50 fw-bold">Создать</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="bg-body-tertiary text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #212529;">
            <a class=" nav-link active text-white-50 fw-bold" href="{{ route('index') }}">DigiCloset</a>
        </div>
        <!-- Copyright -->
    </footer>

@endsection
