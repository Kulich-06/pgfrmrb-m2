@extends('main')
@section('title', 'Создание одежды')
@section('content')

    <div id="alertContainer"></div> {{-- Контейнер для уведомлений --}}

    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h2 class="fw-bold mb-2 text-uppercase">Добавление одежды</h2>
                                <br>
                                <form id="clotchForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" id="name" name="name"
                                        class="form-control form-control-lg mb-3" placeholder="Название" required />
                                    <input type="text" id="size" name="size"
                                        class="form-control form-control-lg mb-3" placeholder="Размер" required />
                                    <input type="file" id="img" name="img" accept="image/*"
                                        class="form-control form-control-lg mb-3" required />

                                    <select name="category_id" id="category" class="form-control form-control-lg mb-3"
                                        required>
                                        <option value="">Выберите категорию</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="color_id" id="color" class="form-control form-control-lg mb-3"
                                        required>
                                        <option value="">Выберите цвет</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="season_id" id="season" class="form-control form-control-lg mb-3"
                                        required>
                                        <option value="">Выберите сезон</option>
                                        @foreach ($seasons as $season)
                                            <option value="{{ $season->id }}">{{ $season->name }}</option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Добавить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let isAuthenticated = @json(auth()->check());

            if (!isAuthenticated) {
                loadGuestData();
            }
        });

        function loadGuestData() {
            let guestColors = JSON.parse(localStorage.getItem("guestColors") || "[]");
            let colorSelect = document.getElementById("color");
            guestColors.forEach(color => {
                let option = document.createElement("option");
                option.value = color.name;
                option.textContent = color.name;
                colorSelect.appendChild(option);
            });

            let guestCategories = JSON.parse(localStorage.getItem("guestCategories") || "[]");
            let categorySelect = document.getElementById("category");
            guestCategories.forEach(category => {
                let option = document.createElement("option");
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        }

        document.getElementById('clotchForm').addEventListener('submit', function(e) {
            let isAuthenticated = @json(auth()->check());

            if (!isAuthenticated) {
                e.preventDefault();
                const imgFile = document.getElementById('img').files[0];

                if (!imgFile) {
                    showAlert("Ошибка: загрузите изображение!", "danger");
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const clotch = {
                        id: Date.now().toString(),
                        name: document.getElementById('name').value,
                        size: document.getElementById('size').value,
                        category: document.getElementById('category').selectedOptions[0].text,
                        color: document.getElementById('color').selectedOptions[0].text,
                        season: document.getElementById('season').selectedOptions[0].text,
                        img: event.target.result
                    };

                    let guestClotches = JSON.parse(localStorage.getItem('guestClotches') || "[]");
                    guestClotches.push(clotch);
                    localStorage.setItem('guestClotches', JSON.stringify(guestClotches));

                    showAlert("Одежда сохранена на этом устройстве!", "success");
                    setTimeout(() => {
                        window.location.href = "{{ route('clotch.index') }}";
                    }, 1500);
                };
                reader.readAsDataURL(imgFile);
            } else {
                e.preventDefault();
                let formData = new FormData(this);

                fetch("{{ route('clotch.store') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            showAlert(data.message || "Одежда успешно добавлена!", "success");
                            setTimeout(() => {
                                window.location.href = "{{ route('clotch.index') }}";
                            }, 1500);
                        } else {
                            showAlert("Ошибка при добавлении!", "danger");
                        }
                    })
                    .catch(error => {
                        console.error("Ошибка:", error);
                        showAlert("Ошибка сети!", "danger");
                    });
            }
        });

        function showAlert(message, type) {
            let alertContainer = document.getElementById("alertContainer");
            alertContainer.innerHTML = "";

            let alertMessage = document.createElement("div");
            alertMessage.className = `alert alert-${type} text-center`;
            alertMessage.style.position = "fixed";
            alertMessage.style.top = "60px";
            alertMessage.style.left = "50%";
            alertMessage.style.transform = "translateX(-50%)";
            alertMessage.style.width = "300px";
            alertMessage.style.zIndex = "1000";
            alertMessage.style.padding = "10px";
            alertMessage.innerText = message;

            alertContainer.appendChild(alertMessage);

            setTimeout(() => {
                alertMessage.remove();
            }, 3000);
        }
    </script>

@endsection
