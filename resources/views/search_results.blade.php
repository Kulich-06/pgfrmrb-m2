@extends('main')

@section('title', 'Результаты поиска')

@section('content')
    <div class="container mt-4">
        <h3>Результаты поиска по запросу: "{{ $query }}"</h3>
        <br>
        @if ($clotches->isEmpty())
            <p>Ничего не найдено.</p>
        @else
            <div class="row">
                @foreach ($clotches as $clotch)
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <img src="{{ asset('storage/app') }}/{{ $clotch->img }}" alt="{{ $clotch->name }}"
                                class="card-img-top" style="height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $clotch->name }}</h5>
                                <p class="card-text">Категория: {{ $clotch->category->name ?? 'Без категории' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
