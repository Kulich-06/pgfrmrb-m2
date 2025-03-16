@extends('main')
@section('title', 'Главная страница')
@section('content')
    <br>
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-row">
            <a href="{{ route('color.create') }}"
                class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать цвет</a>
            <a href="{{ route('category.create') }}"
                class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать категорию</a>
            <a href="{{ route('clotch.create') }}"
                class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Добавить вещь</a>
            <a href="{{ route('collection.create') }}"
                class="btn btn-dark btn-outline-light btn-lg px-5 text-white-50 fw-bold">Создать коллекцию</a>
        </div>
    </div>
    <br><br>
    <h3 style="text-align: center;" class="text-dark-50">Гардероб</h3>
    <br><br>
    <div class="d-flex justify-content-center mb-5 align-items-center">
        <label for="collection-select" class="fw-bold text-dark mx-3">Выберите коллекцию:</label>
        <select id="collection-select" class="form-select form-select-lg" style="width: 250px;">
            <option value="" disabled selected>Выберите коллекцию</option>
            <!-- Для авторизованных пользователей показываем коллекции из базы данных -->
            @if (Auth::check())
                @foreach ($collections as $collection)
                    <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                @endforeach
            @else
                <!-- Для неавторизованных пользователей показываем коллекции из localStorage -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const guestCollections = JSON.parse(localStorage.getItem('guestCollections') || "[]");
                        const collectionSelect = document.getElementById('collection-select');

                        guestCollections.forEach(collection => {
                            let option = document.createElement('option');
                            option.value = collection.id;
                            option.textContent = collection.name;
                            collectionSelect.appendChild(option);
                        });
                    });
                </script>
            @endif
        </select>
        <button id="add-to-collection" class="btn btn-dark btn-outline-light btn-lg mx-3">Добавить в коллекцию</button>
        <button id="select-all" class="btn btn-dark btn-outline-light me-2">Выбрать всё</button>
        <button id="deselect-all" class="btn btn-outline-danger" style="display: none;">Отмена</button>
    </div>

    <div class="container">
        <div class="row" id="clotch-container" style="margin-left: 70px;">
            @foreach ($clotches as $clotch)
                <div class="col-md-3 mb-4">
                    <div class="card clotch-card" data-id="{{ $clotch->id }}"
                        style="height:570px; width: 100%; cursor: pointer;">
                        <img src="{{ asset('storage/app') }}/{{ $clotch->img }}" alt="{{ $clotch->name }}"
                            class="card-img-top" style="height: 100%; width: 100%; object-fit: cover; max-height: 300px;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title d-flex justify-content-between fs-4 fw-bold">
                                {{ $clotch->name }}
                                <form action="{{ route('clotch.destroy', $clotch->id) }}" method="POST"
                                    onsubmit="return confirm('Вы уверены, что хотите удалить эту вещь?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                </form>
                            </h5>
                            <ul class="list-group list-group-flush mt-auto">
                                <li class="list-group-item">Категория: {{ $clotch->category->name ?? 'Без категории' }}
                                </li>
                                <li class="list-group-item">Цвет: {{ $clotch->color->name ?? 'Без цвета' }}</li>
                                <li class="list-group-item">Сезон: {{ $clotch->season->name ?? 'Без сезона' }}</li>
                                <li class="list-group-item">Размер: {{ $clotch->size }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row" id="guest-clotch-container" style="margin-left:70px;"></div>
    </div>
    <br>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const clotchContainer = document.getElementById('clotch-container');
            const guestClotchContainer = document.getElementById('guest-clotch-container');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const addToCollectionBtn = document.getElementById('add-to-collection');
            const collectionSelect = document.getElementById('collection-select');

            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

            // Загружаем вещи из localStorage, если пользователь не авторизован
            if (!isAuthenticated) {
                const guestClotches = JSON.parse(localStorage.getItem('guestClotches') || "[]");

                guestClotches.forEach(clotch => {
                    let clotchCard = document.createElement('div');
                    clotchCard.classList.add('col-md-3', 'mb-4');
                    clotchCard.innerHTML = ` 
                        <div class="card clotch-card" data-id="${clotch.id}" style="height:570px; width: 100%; cursor: pointer;">
                            <img src="${clotch.img}" alt="${clotch.name}" class="card-img-top" style="height: 100%; width: 100%; object-fit: cover; max-height: 300px;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title d-flex justify-content-between fs-4 fw-bold">
                                    ${clotch.name}
                                    <button class="btn btn-outline-danger delete-btn" data-id="${clotch.id}">Удалить</button>
                                </h5>
                                <ul class="list-group list-group-flush mt-auto">
                                    <li class="list-group-item">Категория: ${clotch.category || 'Без категории'}</li>
                                    <li class="list-group-item">Цвет: ${clotch.color || 'Без цвета'}</li>
                                    <li class="list-group-item">Сезон: ${clotch.season || 'Без сезона'}</li>
                                    <li class="list-group-item">Размер: ${clotch.size}</li>
                                </ul>
                            </div>
                        </div>
                    `;
                    guestClotchContainer.appendChild(clotchCard);
                });

                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        let guestClotches = JSON.parse(localStorage.getItem('guestClotches') ||
                            "[]");

                        guestClotches = guestClotches.filter(c => c.id !== id);
                        localStorage.setItem('guestClotches', JSON.stringify(guestClotches));

                        this.closest('.col-md-3').remove();
                    });
                });
            }

            // Логика выбора элементов
            const clotchCards = document.querySelectorAll('.clotch-card');
            clotchCards.forEach(card => {
                card.addEventListener('click', () => {
                    card.classList.toggle('selected');
                    toggleDeselectButton();
                });
            });

            selectAllBtn.addEventListener('click', () => {
                clotchCards.forEach(card => card.classList.add('selected'));
                deselectAllBtn.style.display = 'inline-block';
            });

            deselectAllBtn.addEventListener('click', () => {
                clotchCards.forEach(card => card.classList.remove('selected'));
                deselectAllBtn.style.display = 'none';
            });

            function toggleDeselectButton() {
                const anySelected = document.querySelector('.clotch-card.selected');
                deselectAllBtn.style.display = anySelected ? 'inline-block' : 'none';
            }

            // Логика добавления в коллекцию
            addToCollectionBtn.addEventListener('click', () => {
                const selectedCards = document.querySelectorAll('.clotch-card.selected');
                const collectionId = collectionSelect.value;

                if (collectionId && selectedCards.length > 0) {
                    selectedCards.forEach(card => {
                        const clotchId = card.getAttribute('data-id');
                        // Отправляем запрос на добавление в коллекцию (потребуется маршрут и обработчик на сервере)
                        fetch(`/add-to-collection/${clotchId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    collection_id: collectionId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Вещь успешно добавлена в коллекцию!');
                                }
                            })
                            .catch(error => {
                                console.error('Ошибка:', error);
                                alert('Произошла ошибка при добавлении.');
                            });
                    });
                } else {
                    alert('Пожалуйста, выберите коллекцию и вещи для добавления.');
                }
            });
        });
    </script>
    <style>
        .clotch-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
        }

        .clotch-card.selected {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.3);
            border: 2px solid #28a745;
        }

        .clotch-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Стилизация выпадающего списка и кнопки */
        #collection-select {
            border-radius: 10px;
            border: 1px solid #2c2c2c;
            padding: 10px;
            background-color: #f8f9fa;
            color: #495057;
            font-size: 1rem;
        }

        #add-to-collection {
            border-radius: 10px;
            padding: 10px 20px;
            background-color: #303030;
            color: rgb(160, 160, 160);
            font-size: 1rem;
            border: none;
            font-weight: bold;
        }

        #add-to-collection:hover {
            background-color: #dddddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
