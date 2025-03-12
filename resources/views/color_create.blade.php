@extends("main")
@section("title", "Создание цвета")
@section("content")
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h2 class="fw-bold mb-2 text-uppercase">Добавление цвета</h2>
                                <p class="text-white-50 mb-5"></p>
                                <form action="{{ route('color.store') }}" method="POST">
                                    @csrf
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="Название"/>
                                    </div><br>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-outline-light btn-lg px-5">Добавить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
