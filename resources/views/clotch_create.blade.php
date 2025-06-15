@extends('main')
@section('title', 'Создание одежды')
@section('content')

<div class="container d-flex justify-content-center align-items-center" style="padding-top: 50px; padding-bottom: 60px;">
    <div class="card bg-dark text-white" style="max-width: 450px; width: 100%; border-radius: 1rem;">
        <div class="card-body p-4 p-sm-5">

            <h3 class="fw-bold mb-4 text-uppercase text-center">Добавление одежды</h3>

            {{-- Контейнер для уведомлений --}}
            <div id="alertContainer" style="position: fixed; top: 20px; width: 100%; pointer-events: none; z-index: 1050;"></div>

          <form id="clotchForm" method="POST" action="{{ route('clotch.store') }}" enctype="multipart/form-data" autocomplete="off">

                @csrf

                <div class="mb-3 text-start">
                    <label for="name" class="form-label">Название</label>
                    <input type="text" id="name" name="name" class="form-control bg-opacity-25 border-0 " placeholder="Название" required>
                </div>

                <div class="mb-3 text-start">
                    <label for="size" class="form-label">Размер</label>
                    <input type="text" id="size" name="size" class="form-control bg-opacity-25 border-0 " placeholder="Размер" required>
                </div>

                <div class="mb-3 text-start">
                    <label for="img" class="form-label">Изображение</label>
                    <input type="file" id="img" name="img" accept="image/*" class="form-control bg-opacity-25 border-0 " required>
                </div>

                <div class="mb-3 text-start">
                    <label for="category" class="form-label">Категория</label>
                    <select name="category_id" id="category" class="form-select bg-opacity-25 border-0 " required>
                        <option value="" disabled selected>Выберите категорию</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 text-start">
                    <label for="color" class="form-label">Цвет</label>
                    <select name="color_id" id="color" class="form-select bg-opacity-25 border-0 " required>
                        <option value="" disabled selected>Выберите цвет</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 text-start">
                    <label for="season" class="form-label">Сезон</label>
                    <select name="season_id" id="season" class="form-select bg-opacity-25 border-0 " required>
                        <option value="" disabled selected>Выберите сезон</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-grid">
                    <button class="btn btn-outline-light btn-lg" type="submit">Добавить</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function loadGuestData() {
        // Загрузка цветов из localStorage
        let guestColors = JSON.parse(localStorage.getItem("guestColors") || "[]");
        let colorSelect = document.getElementById("color");

        // Очищаем все опции кроме первой "Выберите цвет"
        colorSelect.options.length = 1;

        guestColors.forEach(color => {
            let option = document.createElement("option");
            option.value = color.name; // или color.id если сохраняешь id
            option.textContent = color.name;
            colorSelect.appendChild(option);
        });

           // Загрузка категорий из localStorage
    let guestCategories = JSON.parse(localStorage.getItem("guestCategories") || "[]");
    let categorySelect = document.getElementById("category");

    // Очищаем все опции кроме первой "Выберите категорию"
    categorySelect.options.length = 1;

    guestCategories.forEach(category => {
        let option = document.createElement("option");
        option.value = category.id; // используем id
        option.textContent = category.name; // читаемое название
        categorySelect.appendChild(option);
    });
}

    function resizeImage(base64Str, maxWidth, maxHeight, callback) {
        let img = new Image();
        img.onload = () => {
            let canvas = document.createElement('canvas');
            let ctx = canvas.getContext('2d');

            let ratio = Math.min(maxWidth / img.width, maxHeight / img.height);
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;

            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            callback(canvas.toDataURL('image/jpeg', 0.7));
        };
        img.src = base64Str;
    }

    function showAlert(message, type) {
        let alertContainer = document.getElementById("alertContainer");
        alertContainer.innerHTML = "";

        let alertMessage = document.createElement("div");
        alertMessage.className = `alert alert-${type} alert-dismissible fade show text-center`;
        alertMessage.style.position = "fixed";
        alertMessage.style.top = "20px";
        alertMessage.style.left = "50%";
        alertMessage.style.transform = "translateX(-50%)";
        alertMessage.style.minWidth = "300px";
        alertMessage.style.zIndex = "1050";
        alertMessage.style.pointerEvents = "auto";
        alertMessage.innerText = message;

        let btnClose = document.createElement('button');
        btnClose.type = 'button';
        btnClose.className = 'btn-close btn-close-white';
        btnClose.setAttribute('aria-label', 'Close');
        btnClose.onclick = () => alertMessage.remove();
        alertMessage.appendChild(btnClose);

        alertContainer.appendChild(alertMessage);

        setTimeout(() => {
            alertMessage.classList.remove('show');
            alertMessage.classList.add('hide');
            setTimeout(() => alertMessage.remove(), 500);
        }, 4000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const isAuthenticated = @json(auth()->check());
        console.log('Auth:', isAuthenticated);

        if (!isAuthenticated) {
            loadGuestData();
        }

        document.getElementById('clotchForm').addEventListener('submit', function(e) {
            console.log('Submit triggered');
            if (!isAuthenticated) {
                e.preventDefault();

                const imgFile = document.getElementById('img').files[0];
                if (!imgFile) {
                    showAlert("Ошибка: загрузите изображение!", "danger");
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    resizeImage(event.target.result, 300, 300, function(resizedImg) {
                        const clotch = {
                            id: Date.now().toString(),
                            name: document.getElementById('name').value,
                            size: document.getElementById('size').value,
                            category: document.getElementById('category').selectedOptions[0].text,
                            color: document.getElementById('color').selectedOptions[0].text,
                            season: document.getElementById('season').selectedOptions[0].text,
                            img: resizedImg
                        };

                        let guestClotches = JSON.parse(localStorage.getItem('guestClotches') || "[]");
                        guestClotches.push(clotch);

                        try {
                            localStorage.setItem('guestClotches', JSON.stringify(guestClotches));
                            showAlert("Одежда сохранена на этом устройстве!", "success");
                            setTimeout(() => {
                                window.location.href = "{{ route('clotch.index') }}";
                            }, 1500);
                        } catch (e) {
                            console.error('Ошибка при сохранении в localStorage:', e);
                            showAlert("Ошибка: недостаточно места для сохранения!", "danger");
                        }
                    });
                };
                reader.readAsDataURL(imgFile);
            } else {
                // Авторизованный пользователь
                e.preventDefault();
                let formData = new FormData(this);

               fetch("{{ route('clotch.store') }}", {
    method: "POST",
    body: formData,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
    }
})

                .then(async response => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Ошибка сервера:', errorText);
                        throw new Error('Сетевая ошибка: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.status === "success") {
                        showAlert(data.message || "Одежда успешно добавлена!", "success");
                        setTimeout(() => {
                            window.location.href = "{{ route('clotch.index') }}";
                        }, 1500);
                    } else {
                        showAlert(data.message || "Ошибка при добавлении!", "danger");
                    }
                })
                .catch(error => {
                    console.error("Ошибка:", error);
                    showAlert("Ошибка сети! " + error.message, "danger");
                });
            }
        });
    });
</script>


@endsection
