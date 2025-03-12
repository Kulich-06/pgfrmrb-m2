@extends("main")
@section("title","Создание одежды")
@section("content")
<section class="vh-100 bg-image">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <h2 class="fw-bold mb-2 text-uppercase">Добавление одежды</h2>
                            <form id="clotchForm" action="{{ auth()->check() ? route('clotch.store') : '#' }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" id="name" name="name" class="form-control form-control-lg mb-3" placeholder="Название" required />
                                <input type="text" id="size" name="size" class="form-control form-control-lg mb-3" placeholder="Размер" required />
                                <input type="file" id="img" name="img" accept="image/*" class="form-control form-control-lg mb-3" required />

                                <select name="category_id" id="category" class="form-control form-control-lg mb-3" required>
                                    <option value="">Выберите категорию</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <select name="color_id" id="color" class="form-control form-control-lg mb-3" required>
                                    <option value="">Выберите цвет</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>

                                <select name="season_id" id="season" class="form-control form-control-lg mb-3" required>
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
document.getElementById('clotchForm').addEventListener('submit', function (e) {
    @guest
        e.preventDefault();
        const reader = new FileReader();
        const imgFile = document.getElementById('img').files[0];

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

            let guestClotches = JSON.parse(localStorage.getItem('guestClotches')) || [];
            guestClotches.push(clotch);
            localStorage.setItem('guestClotches', JSON.stringify(guestClotches));
            alert('Одежда сохранена на этом устройстве!');
            window.location.href = "{{ route('clotch.index') }}";
        };
        reader.readAsDataURL(imgFile);
    @endguest
});
</script>
@endsection
