@extends('main')
@section('title', 'Коллекции')
@section('content')
    <h3 class="text-center text-dark-50 mt-5">Коллекции</h3> <!-- Добавлен отступ от шапки -->
    <br><br>
    <div class="container">
        <div class="row" style="margin-left:70px;">
            <!-- Здесь будут отображаться коллекции из localStorage для неавторизованных пользователей -->
            <div id="collectionsContainer"></div>

            <!-- Для авторизованных пользователей вы можете отображать коллекции из базы данных -->
            @foreach ($collections as $collection)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $collection->name }}</h5>
                            <div class="mt-auto d-flex justify-content-between">
                                <a href="#" class="btn btn-outline-info">Посмотреть</a>
                                <form action="{{ route('collection.destroy', $collection->id) }}" method="POST"
                                    onsubmit="return confirm('Вы уверены, что хотите удалить эту коллекцию?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                </form>

                                <!-- Кнопка "Посмотреть" -->

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Форма для добавления коллекции (для неавторизованных пользователей) -->
        @guest
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button class="btn btn-outline-light btn-lg" id="addCollectionBtn">Добавить коллекцию</button>
                </div>
            </div>
        @endguest
    </div>

    <script>
        // Загружаем коллекции из localStorage
        let guestCollections = JSON.parse(localStorage.getItem('guestCollections')) || [];

        // Отображаем коллекции из localStorage
        guestCollections.forEach(collection => {
            const collectionCard = `
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${collection.name}</h5>
                            <div class="mt-auto d-flex justify-content-between">
                                <button class="btn btn-outline-danger" onclick="deleteCollection('${collection.id}')">Удалить</button>
                                <button class="btn btn-outline-info" onclick="viewCollection('${collection.id}')">Посмотреть</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('collectionsContainer').insertAdjacentHTML('beforeend', collectionCard);
        });

        // Функция для удаления коллекции
        function deleteCollection(collectionId) {
            guestCollections = guestCollections.filter(collection => collection.id !== collectionId);
            localStorage.setItem('guestCollections', JSON.stringify(guestCollections));
            window.location.reload();
        }

        // Функция для просмотра коллекции
        function viewCollection(collectionId) {
            alert('Вы хотите просмотреть коллекцию с ID: ' + collectionId);
            // Здесь можно добавить логику для перехода на страницу просмотра
            // например, перенаправление:
            // window.location.href = '/collections/' + collectionId;
        }

        // Обработчик кнопки добавления коллекции
        document.getElementById('addCollectionBtn').addEventListener('click', function() {
            const collectionName = prompt('Введите название коллекции:');
            if (collectionName) {
                const newCollection = {
                    id: Date.now().toString(),
                    name: collectionName
                };
                guestCollections.push(newCollection);
                localStorage.setItem('guestCollections', JSON.stringify(guestCollections));
                alert('Коллекция сохранена на устройстве!');
                window.location.reload(); // Обновляем страницу для отображения новой коллекции
            }
        });
    </script>
@endsection
