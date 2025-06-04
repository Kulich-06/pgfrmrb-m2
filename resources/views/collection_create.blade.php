@extends('main')
@section('title', 'Создание коллекции')

@section('content')

    <div class="container d-flex justify-content-center align-items-center" style="padding-top: 220px; padding-bottom: 60px;">
        <div class="card bg-dark text-white" style="max-width: 450px; width: 100%; border-radius: 1rem;">
            <div class="card-body p-4 p-sm-5 text-center">

                <h3 class="fw-bold mb-4 text-uppercase">Создание коллекции</h3>

                {{-- Вывод ошибок --}}
                @if ($errors->any())
                    <div class="alert alert-danger text-start">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Форма создания коллекции --}}
                <form action="{{ route('collection.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3 text-start">
                        <label for="name" class="form-label">Название коллекции</label>
                        <input type="text" id="name" name="name"
                            class="form-control bg-opacity-25 border-0  @error('name') is-invalid @enderror"
                            placeholder="Введите название коллекции" value="{{ old('name') }}" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-outline-light">Добавить</button>
                    </div>
                </form>

                {{-- Кнопка назад к списку коллекций --}}
                <div>
                    <a href="{{ route('collection.index') }}" class="text-white-50">← Вернуться к коллекциям</a>
                </div>

            </div>
        </div>
    </div>

@endsection
