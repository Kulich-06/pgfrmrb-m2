<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        html,
        body {
            height: 100%;
            /* Делаем страницу на всю высоту */
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Минимальная высота на весь экран */
        }

        .content {
            flex: 1;
            /* Занимает всю доступную высоту, выталкивая футер вниз */
        }

        footer {
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 1 px 0;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <header>
            <nav class="navbar bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand text-white fw-bold">DigiCloset</a>
                    <a class="nav-link active text-white-50 fw-bold" aria-current="page"
                        href="{{ route('index') }}">Главная</a>
                    <a class="nav-link active text-white-50 fw-bold" aria-current="page"
                        href="{{ route('collection.index') }}">Коллекции</a>
                    @guest
                        <a href="{{ route('register.form') }}" class="nav-link active text-white-50 fw-bold">Регистрация</a>
                        <a href="{{ route('login.form') }}" class="nav-link active text-white-50 fw-bold">Вход</a>
                    @endguest
                    @auth
                        <a href="{{ route('profile') }}" class="nav-link active text-white-50 fw-bold">Профиль</a>
                        <a href="{{ route('logout') }}" class="nav-link active text-white-50 fw-bold">Выйти</a>
                    @endauth
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="поиск" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Поиск</button>
                    </form>
                </div>
            </nav>
        </header>
        <div id="alertContainer" class="container mt-3"></div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="content">
            @yield('header')
            @yield('content')
        </div>

        <footer class="text-center text-lg-start">
            <div class="text-center p-3">
                <a class="nav-link active text-white-50 fw-bold" href="{{ route('index') }}">DigiCloset</a>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
