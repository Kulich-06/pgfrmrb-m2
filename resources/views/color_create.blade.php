@extends('main')
@section('title', 'Создание цвета')
@section('content')

    <div id="alertContainer"
        style="position: fixed; top: 70px; left: 50%; transform: translateX(-50%); width: 320px; z-index: 1050;"></div>

    <div class="container d-flex justify-content-center align-items-center" style="padding-top:250px; padding-bottom: 60px;">
        <div class="card bg-dark text-white" style="max-width: 400px; width: 100%; border-radius: 1rem;">
            <div class="card-body p-4 p-sm-5">

                <h3 class="text-center mb-4 fw-bold text-uppercase">Добавление цвета</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="colorForm" autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Название цвета</label>
                        <input type="text" id="name" name="name" class="form-control bg-opacity-25 border-0 "
                            placeholder="Введите название цвета" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-outline-light">Добавить</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

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
                    const alertContainer = document.getElementById("alertContainer");
                    alertContainer.innerHTML = "";

                    const alertMessage = document.createElement("div");
                    alertMessage.className = "alert text-center small";
                    alertMessage.style.padding = "10px";
                    alertMessage.style.borderRadius = "6px";

                    if (data.status === "guest") {
                        let guestColors = JSON.parse(localStorage.getItem("guestColors")) || [];
                        guestColors.push({
                            name: data.name
                        });
                        localStorage.setItem("guestColors", JSON.stringify(guestColors));

                        alertMessage.classList.add("alert-info");
                        alertMessage.textContent = "Цвет сохранён локально на устройстве!";
                    } else if (data.status === "success" || data.message) {
                        alertMessage.classList.add("alert-success");
                        alertMessage.textContent = data.message || "Цвет успешно добавлен!";
                    } else if (data.errors) {
                        alertMessage.classList.add("alert-danger");
                        alertMessage.innerHTML = Object.values(data.errors).flat().join('<br>');
                    } else {
                        alertMessage.classList.add("alert-warning");
                        alertMessage.textContent = "Произошла ошибка. Попробуйте снова.";
                    }

                    alertContainer.appendChild(alertMessage);

                    setTimeout(() => alertMessage.remove(), 3500);

                    if (data.status === "success" || data.status === "guest") {
                        document.getElementById("name").value = "";
                    }
                })
                .catch(error => {
                    console.error("Ошибка:", error);
                });
        });
    </script>

@endsection
