@extends('main')
@section('title', 'Коллекции')
@section('content')
    <style>
        .collection-card {
            background-color: #fff;
            color: #222;
            border: 1px solid #ccc;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .collection-card:hover {
            border-color: #888;
            background-color: #f7f7f7;
        }

        .collection-card.selected {
            border: 2px solid #555;
            background-color: #e2e2e2;
        }

        .btn-dark-outline {
            color: #222;
            border: 1px solid #555;
            background-color: #fff;
            transition: 0.3s;
        }

        .btn-dark-outline:hover {
            background-color: #eee;
            border-color: #333;
        }

        .select-all-link {
            color: #222;
            cursor: pointer;
            text-decoration: underline;
            margin-bottom: 15px;
            display: inline-block;
        }

        .action-buttons {
            margin-top: 20px;
        }

        .action-buttons .btn {
            margin-right: 10px;
        }
    </style>

    <h3 class="text-center text-secondary mt-3">Коллекции</h3>

    <div class="container">
        <div class="mb-3">
            <span class="select-all-link" onclick="toggleSelectAll()">Выбрать все</span>
            <button id="deleteSelectedBtn" class="btn btn-dark-outline d-none" onclick="deleteSelected()">Удалить
                выбранные</button>
        </div>

        <div class="row" id="collectionsContainer">
            @foreach ($collections as $collection)
                <div class="col-md-3 mb-4">
                    <div class="card collection-card" data-id="{{ $collection->id }}" onclick="toggleSelect(this)">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $collection->name }}</h5>
                            <div class="mt-auto d-flex justify-content-between">
                                <a href="#" class="btn btn-dark-outline"
                                    onclick="event.stopPropagation()">Посмотреть</a>

                                <form action="{{ route('collection.destroy', $collection->id) }}" method="POST"
                                    onsubmit="return confirm('Вы уверены, что хотите удалить эту коллекцию?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-dark-outline">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Убираем кнопку добавления коллекции для гостей --}}
    </div>

    <script>
        const selectedIds = new Set();
        const deleteBtn = document.getElementById('deleteSelectedBtn');

        function toggleSelect(card) {
            const id = card.dataset.id;
            card.classList.toggle('selected');

            if (card.classList.contains('selected')) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }

            deleteBtn.classList.toggle('d-none', selectedIds.size === 0);
        }

        function toggleSelectAll() {
            const cards = document.querySelectorAll('.collection-card');
            const allSelected = Array.from(cards).every(card => card.classList.contains('selected'));

            cards.forEach(card => {
                const id = card.dataset.id;
                if (allSelected) {
                    card.classList.remove('selected');
                    selectedIds.delete(id);
                } else {
                    card.classList.add('selected');
                    selectedIds.add(id);
                }
            });

            deleteBtn.classList.toggle('d-none', selectedIds.size === 0);
        }

        function deleteSelected() {
            if (!confirm('Удалить выбранные коллекции?')) return;

            // Создаем массив промисов для всех запросов
            const promises = [];

            selectedIds.forEach(id => {
                const promise = fetch(`/collections/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                }).then(response => {
                    if (response.ok) {
                        // Удаляем карточку из DOM
                        const cardElem = document.querySelector(`[data-id="${id}"]`);
                        if (cardElem) {
                            cardElem.parentElement.remove();
                        }
                    } else {
                        alert(`Ошибка при удалении коллекции с ID: ${id}`);
                    }
                });
                promises.push(promise);
            });

            Promise.all(promises).then(() => {
                selectedIds.clear();
                deleteBtn.classList.add('d-none');
            });
        }

        // ======== Гостевые коллекции ========
        @guest
        let guestCollections = JSON.parse(localStorage.getItem('guestCollections')) || [];

        guestCollections.forEach(collection => {
            const card = `
            <div class="col-md-3 mb-4">
                <div class="card collection-card" data-id="${collection.id}" onclick="toggleSelect(this)">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${collection.name}</h5>
                        <div class="mt-auto d-flex justify-content-between">
                            <button class="btn btn-dark-outline" onclick="event.stopPropagation(); deleteCollection('${collection.id}')">Удалить</button>
                            <button class="btn btn-dark-outline" onclick="event.stopPropagation(); viewCollection('${collection.id}')">Посмотреть</button>
                        </div>
                    </div>
                </div>
            </div>`;
            document.getElementById('collectionsContainer').insertAdjacentHTML('beforeend', card);
        });

        function deleteCollection(collectionId) {
            guestCollections = guestCollections.filter(c => c.id !== collectionId);
            localStorage.setItem('guestCollections', JSON.stringify(guestCollections));
            location.reload();
        }

        function viewCollection(collectionId) {
            alert('Вы выбрали коллекцию с ID: ' + collectionId);
            // window.location.href = '/collections/' + collectionId;
        }
        @endguest
    </script>
@endsection
