@extends("main")
@section("title","Регистрация")
@section("content")
<div class="container">
  <section class="vh-100 bg-image">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
      <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-dark text-white" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">
                <h2 class="fw-bold mb-2 text-uppercase">Регистрация</h2>
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

                <form action="{{ route('register.store') }}" method="POST">
                  @csrf

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="name" name="name" class="form-control form-control-lg" value="{{old('name')}}" placeholder="Имя" />
                    <label class="form-label" for="name"></label>
                  </div>

                  <div data-mdb-input-init class="form-outline form-white mb-4">
                    <input type="text" id="login" name="login" class="form-control form-control-lg" value="{{old('login')}}" placeholder="Логин" />
                    <label class="form-label" for="login"></label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{old('email')}}" placeholder="Email" />
                    <label class="form-label" for="email"></label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="tel" id="phone" name="phone" class="form-control form-control-lg" value="{{old('phone')}}" placeholder="Номер телефона" />
                    <label class="form-label" for="phone"></label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    </label> <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Пароль" />
                    <label class="form-label" for="password">
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5">Зарегистрироваться</button>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0 text-white-50">Уже есть аккаунт? <a href="{{route('login.form')}}" class="text-white-50 fw-bold"><u>Войти</u></a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection