@extends('main')
@section('title', 'Главная страница')
@section('content')
    <br>
    <!-- Новый блок с "ссылками-кнопками" -->
    <div class="btn-group-links">
        <a href="{{ route('color.create') }}">Создать цвет</a>
        <a href="{{ route('category.create') }}">Создать категорию</a>
        <a href="{{ route('clotch.create') }}">Добавить вещь</a>
        <a href="{{ route('collection.create') }}">Создать коллекцию</a>
    </div>

    <br><br>
    <h3 class="text-center text-secondary">Гардероб</h3>
    <br><br>
    <div class="d-flex justify-content-center align-items-center mb-5 gap-3 flex-wrap">
        <label for="collection-select" class="fw-bold text-dark mx-3 mb-0">Выберите коллекцию:</label>
        <select id="collection-select" class="form-select form-select-lg" style="width: 250px;">
            <option value="" disabled selected>Выберите коллекцию</option>
            @if (Auth::check())
                @foreach ($collections as $collection)
                    <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                @endforeach
            @else
                {{-- Опции для гостя будут загружаться через JS --}}
            @endif
        </select>
        <button id="add-to-collection">Добавить в коллекцию</button>
        <button id="select-all">Выбрать всё</button>
        <button id="deselect-all" style="display: none;">Отмена</button>
    </div>

    <div class="container">
        <div class="row" id="clotch-container" style="margin-left: 70px;">
            @foreach ($clotches as $clotch)
                <div class="col-md-3 mb-4">
                    <div class="card clotch-card" data-id="{{ $clotch->id }}"
                        style="height:570px; width: 100%; cursor: pointer;">
                        <img src="{{ asset('storage/app') }}/{{ $clotch->img }}" alt="{{ $clotch->name }}"
                            class="card-img-top" style="height: 300px; width: 100%; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title d-flex justify-content-between fs-4 fw-bold mb-3">
                                {{ $clotch->name }}
                                <form action="{{ route('clotch.destroy', $clotch->id) }}" method="POST"
                                    onsubmit="return confirm('Вы уверены, что хотите удалить эту вещь?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-sm">Удалить</button>
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
        <div class="row" id="guest-clotch-container" style="margin-left: 70px;"></div>
    </div>
    <br>

    {{-- Передаём значение аутентификации в data-атрибут --}}
    <script>
        window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        window.guestCollections = JSON.parse(localStorage.getItem('guestCollections') || "[]");
        window.guestClotches = JSON.parse(localStorage.getItem('guestClotches') || "[]");
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const clotchContainer = document.getElementById('clotch-container');
            const guestClotchContainer = document.getElementById('guest-clotch-container');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const addToCollectionBtn = document.getElementById('add-to-collection');
            const collectionSelect = document.getElementById('collection-select');

            const isAuthenticated = window.isAuthenticated;

            if (!isAuthenticated) {
                // Заполняем селект коллекциями гостя
                window.guestCollections.forEach(collection => {
                    let option = document.createElement('option');
                    option.value = collection.id;
                    option.textContent = collection.name;
                    collectionSelect.appendChild(option);
                });

                // Загружаем вещи гостя из localStorage
                window.guestClotches.forEach(clotch => {
                    let clotchCard = document.createElement('div');
                    clotchCard.classList.add('col-md-3', 'mb-4');
                    clotchCard.innerHTML = `
                        <div class="card clotch-card" data-id="${clotch.id}" style="height:570px; width: 100%; cursor: pointer;">
                            <img src="${clotch.img}" alt="${clotch.name}" class="card-img-top" style="height: 300px; width: 100%; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title d-flex justify-content-between fs-4 fw-bold mb-3">
                                    ${clotch.name}
                                    <button class="btn btn-delete btn-sm delete-btn" data-id="${clotch.id}">Удалить</button>
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

                // Удаление вещи из localStorage и DOM
                guestClotchContainer.addEventListener('click', e => {
                    if (e.target.classList.contains('delete-btn')) {
                        const id = e.target.dataset.id;
                        let guestClotches = window.guestClotches;
                        const filtered = guestClotches.filter(c => c.id !== id);
                        localStorage.setItem('guestClotches', JSON.stringify(filtered));
                        e.target.closest('.col-md-3').remove();
                    }
                });
            }

            // Обработчики выделения карточек
            function updateDeselectBtn() {
                const anySelected = document.querySelector('.clotch-card.selected');
                deselectAllBtn.style.display = anySelected ? 'inline-block' : 'none';
            }

            function toggleCardSelection(card) {
                card.classList.toggle('selected');
                updateDeselectBtn();
            }

            const allCards = document.querySelectorAll('.clotch-card');
            allCards.forEach(card => {
                card.addEventListener('click', () => toggleCardSelection(card));
            });

            selectAllBtn.addEventListener('click', () => {
                allCards.forEach(card => card.classList.add('selected'));
                updateDeselectBtn();
            });

            deselectAllBtn.addEventListener('click', () => {
                allCards.forEach(card => card.classList.remove('selected'));
                updateDeselectBtn();
            });

            // Добавление вещей в коллекцию
            addToCollectionBtn.addEventListener('click', () => {
                const selectedCards = document.querySelectorAll('.clotch-card.selected');
                const collectionId = collectionSelect.value;

                if (collectionId && selectedCards.length > 0) {
                    selectedCards.forEach(card => {
                        const clotchId = card.getAttribute('data-id');
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
                                } else {
                                    alert('Не удалось добавить вещь в коллекцию.');
                                }
                            })
                            .catch(() => alert('Произошла ошибка при добавлении.'));
                    });
                } else {
                    alert('Пожалуйста, выберите коллекцию и вещи для добавления.');
                }
            });
        });
    </script>

    <style>
        /* Новый стиль для блока ссылок-кнопок */
        .btn-group-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn-group-links a {
            text-decoration: none;
            color: #555;
            font-weight: 600;
            padding: 6px 12px;
            border-bottom: 2px solid transparent;
            transition: color 0.3s ease, border-color 0.3s ease;
            cursor: pointer;
        }

        .btn-group-links a:hover {
            color: #222;
            border-bottom-color: #222;
        }

        /* Карточки */
        .clotch-card {
            transition: box-shadow 0.25s ease, border-color 0.25s ease;
            cursor: pointer;
            border-radius: 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.05);
        }

        .clotch-card.selected {
            box-shadow: 0 0 12px rgba(120, 120, 120, 0.2);
            border-color: #bbb;
        }

        .clotch-card:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
            border-color: #aaa;
        }

        .card-img-top {
            border-radius: 12px 12px 0 0;
            object-fit: cover;
            height: 300px;
            width: 100%;
            transition: none;
            /* убрали трансформации */
        }

        /* Убираем масштабирование картинки при наведении */
        .clotch-card:hover .card-img-top {
            transform: none;
        }

        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 16px;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-delete {
            background: transparent;
            border: 1.5px solid #a33a3a;
            color: #a33a3a;
            border-radius: 5px;
            padding: 4px 10px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #a33a3a;
            color: white;
        }

        .list-group-item {
            border: none;
            padding-left: 0;
            padding-right: 0;
            color: #555;
            font-size: 0.9rem;
        }

        #collection-select {
            border-radius: 10px;
            border: 1px solid #bbb;
            padding: 8px 12px;
            background-color: #fafafa;
            color: #444;
            font-size: 1rem;
            max-width: 250px;
            transition: border-color 0.3s ease;
        }

        #collection-select:focus {
            border-color: #888;
            outline: none;
        }

        #add-to-collection,
        #select-all,
        #deselect-all {
            background: none;
            border: 1.5px solid transparent;
            color: #555;
            font-weight: 600;
            padding: 8px 15px;
            cursor: pointer;
            transition: color 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
            border-radius: 6px;
            user-select: none;
        }

        #add-to-collection:hover,
        #select-all:hover {
            color: #222;
            border-color: #ddd;
            background-color: #f5f5f5;
            text-decoration: none;
        }

        #deselect-all {
            color: #a33a3a;
            border: 1.5px solid transparent;
        }

        #deselect-all:hover {
            background-color: #a33a3a;
            color: white;
            border-color: #a33a3a;
        }

        /* Мобильная адаптивность */
        @media (max-width: 768px) {

            #clotch-container,
            #guest-clotch-container {
                margin-left: 0 !important;
                padding: 0 10px;
            }

            .btn-group-links {
                flex-wrap: wrap;
                gap: 10px;
                padding: 0 10px;
            }

            .clotch-card {
                height: auto !important;
            }

            .card-img-top {
                height: 220px;
            }

            .card-title {
                font-size: 1rem;
            }

            #collection-select {
                max-width: 100%;
                width: 100%;
                margin-bottom: 10px;
            }

            #add-to-collection,
            #select-all,
            #deselect-all {
                width: 100%;
                margin-bottom: 8px;
                text-align: center;
            }

            .d-flex.justify-content-center.align-items-center.mb-5.gap-3.flex-wrap {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endsection
