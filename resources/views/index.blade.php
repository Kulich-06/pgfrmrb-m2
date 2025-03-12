@extends("main")
@section("title", "Главная страница")
@section("content")
    <br>
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-row">
            <a href="{{route('color.create')}}" class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Сggg цвет</a>
            <a href="{{route('category.create')}}" class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать категорию</a>
            <a href="{{route('clotch.create')}}" class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать вещь</a>
            <a href="{{route('collection.create')}}" class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать коллекцию</a>
        </div>
    </div><br><br>
    <h3 style=" text-align: center; text-dark-50">Гардероб</h3>
    <br><br>
    <div class="container">
        <div class="row" style="margin-left:70px;">
            @foreach ($clotches as $clotch)
                <div class="col-md-4">
                    <div class="card mb-4" style="height:570px; width: 300px;"> <!-- Фиксированная высота карточки -->
                        <img src="{{ asset('storage/app') }}/{{$clotch->img}}" alt="{{ $clotch->name }}" class="card-img-top" style="height: 100%; width: 295px; object-fit: cover; max-height: 300px; "> <!-- Фиксированная высота и свойство object-fit -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title d-flex justify-content-between fs-4 fw-bold">
                            {{ $clotch->name }}
                            <form action="{{ route('clotch.destroy', $clotch->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту вещь?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Удалить</button>
                            </form>
                        </h5>
                            <ul class="list-group list-group-flush mt-auto"> <!-- mt-auto для выравнивания списка внизу карточки -->
                                <li class="list-group-item">Категория: {{ $clotch->category->name ?? 'Без категории' }}</li>
                                <li class="list-group-item">Цвет: {{ $clotch->color->name ?? 'Без цвета' }}</li>
                                <li class="list-group-item">Сезон: {{ $clotch->season->name ?? 'Без сезона' }}</li>
                                <li class="list-group-item">Размер: {{ $clotch->size }}</li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
<br>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clotchContainer = document.getElementById('clotch-container');
        const localClotches = JSON.parse(localStorage.getItem('guestClotches')) || [];
    
        localClotches.forEach(clotch => {
            clotchContainer.insertAdjacentHTML('beforeend', `
                <div class="col-md-4 clotch-card">
                    <div class="card mb-4" style="height:570px; width: 300px;">
                        <img src="${clotch.img}" alt="${clotch.name}" class="card-img-top" style="height: 100%; width: 295px; object-fit: cover; max-height: 300px;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title d-flex justify-content-between fs-4 fw-bold">
                                ${clotch.name}
                                <button onclick="removeGuestClotch('${clotch.id}')" class="btn btn-outline-danger">Удалить</button>
                            </h5>
                            <ul class="list-group list-group-flush mt-auto">
                                <li class="list-group-item">Категория: ${clotch.category}</li>
                                <li class="list-group-item">Цвет: ${clotch.color}</li>
                                <li class="list-group-item">Сезон: ${clotch.season}</li>
                                <li class="list-group-item">Размер: ${clotch.size}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `);
        });
    });
    
    function removeGuestClotch(id) {
        let guestClotches = JSON.parse(localStorage.getItem('guestClotches')) || [];
        guestClotches = guestClotches.filter(item => item.id !== id);
        localStorage.setItem('guestClotches', JSON.stringify(guestClotches));
        location.reload();
    }
    </script>
@endsection