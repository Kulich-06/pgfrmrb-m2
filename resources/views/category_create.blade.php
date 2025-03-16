@extends('main')
@section('title', 'Создание категории')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <h2 class="fw-bold mb-2 text-uppercase">Добавление категории</h2>
                                <br><br>
                                <!-- Добавили контейнер для алертов -->
                                <div id="alertContainer"></div>

                                <form id="categoryForm">
                                    @csrf
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name" name="name"
                                            class="form-control form-control-lg" placeholder="Название" required />
                                    </div>
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-outline-light btn-lg px-5">Добавить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("categoryForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('category.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Ответ сервера:", data);

                    let alertContainer = document.getElementById("alertContainer");
                    alertContainer.innerHTML = ""; // Очищаем предыдущие сообщения

                    let alertMessage = document.createElement("div");
                    alertMessage.className = "alert alert-info text-center"; // Добавляем стили Bootstrap
                    alertMessage.style.position = "fixed";
                    alertMessage.style.top = "60px"; // Отступ от шапки
                    alertMessage.style.left = "50%";
                    alertMessage.style.transform = "translateX(-50%)";
                    alertMessage.style.width = "300px";
                    alertMessage.style.zIndex = "1000";

                    if (data.status === "guest") {
                        let guestCategories = JSON.parse(localStorage.getItem("guestCategories")) || [];
                        guestCategories.push(data.name);
                        localStorage.setItem("guestCategories", JSON.stringify(guestCategories));

                        alertMessage.innerText = "Категория сохранена на устройстве!";
                    } else if (data.status === "success") {
                        alertMessage.innerText = data.message;
                    }

                    alertContainer.appendChild(alertMessage);

                    // Убираем сообщение через 3 секунды
                    setTimeout(() => {
                        alertMessage.remove();
                    }, 3000);

                    document.getElementById("name").value = "";
                })
                .catch(error => console.error("Ошибка:", error));
        });
    </script>

@endsection
