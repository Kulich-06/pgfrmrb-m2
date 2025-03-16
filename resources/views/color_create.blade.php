@extends('main')
@section('title', 'Создание цвета')
@section('content')

    <div id="alertContainer"></div>

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
                                <h2 class="fw-bold mb-2 text-uppercase">Добавление цвета</h2>
                                <br>
                                <form id="colorForm">
                                    @csrf
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name" name="name"
                                            class="form-control form-control-lg" placeholder="Название" required />
                                    </div><br>
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
        document.getElementById("colorForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('color.store') }}", {
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
                    alertMessage.className = "alert text-center";
                    alertMessage.style.position = "fixed";
                    alertMessage.style.top = "60px"; // Отступ от шапки
                    alertMessage.style.left = "50%";
                    alertMessage.style.transform = "translateX(-50%)";
                    alertMessage.style.width = "300px";
                    alertMessage.style.zIndex = "1000";
                    alertMessage.style.padding = "10px";

                    if (data.status === "guest") {
                        let guestColors = JSON.parse(localStorage.getItem("guestColors")) || [];
                        guestColors.push({
                            name: data.name
                        });
                        localStorage.setItem("guestColors", JSON.stringify(guestColors));

                        alertMessage.innerText = "Цвет сохранен на устройстве!";
                        alertMessage.classList.add("alert-info");
                    } else if (data.status === "success" || data.message) {
                        alertMessage.innerText = data.message || "Цвет успешно добавлен!";
                        alertMessage.classList.add("alert-success");
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
