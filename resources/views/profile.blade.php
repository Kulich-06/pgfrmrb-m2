@extends('main')
@section('title', 'Профиль')
@section('content')
    <div class="container">
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-md-12 col-xl-3">
                        <div class="card bg-dark" style="border-radius: 15px;">
                            <div class="card-body text-center text-white">
                                <h4 class="mb-2 ">Имя: {{ Auth::user()->name }} <br> Логин: {{ Auth::user()->login }}</h4>
                                <p class="text-muted mb-2 text-white-50">Почта <span class="mx-2">|</span>
                                    <a>{{ Auth::user()->email }}</a></p>
                                <div class="d-flex justify-content-between text-center mt-1 mb-2">
                                    <div>
                                        <p class="mb-2 h5"></p>
                                        <p class="text-muted mb-0"></p>
                                    </div>
                                    <div class="px-3">
                                        <p class="mb-2 h5">Зарегистрирован:</p>
                                        <p class="text-muted mb-0 text-white-50">{{ Auth::user()->created_at }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-2 h5"></p>
                                        <p class="text-muted mb-0"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
