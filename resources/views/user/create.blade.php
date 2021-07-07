@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Создание пользователя</h1>
    <form class="product-form" action="{{ route("users.store") }}" enctype="multipart/form-data" method="POST">
        @csrf
        @include("layouts.base.parts.errors")
        <label for="role">Роль</label>
        <select name="role" id="role">
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>

        <label for="name">Имя</label>
        <input type="text" name="name" placeholder="Имя" id="name">
        <label for="email">Почта</label>
        <input type="email" name="email" placeholder="Почта" id="email">
        <label for="phone">Телефон</label>
        <input type="text" name="phone" placeholder="Телефон" id="phone">
        <label for="password">Пароль</label>
        <input type="text" name="password" placeholder="Пароль" id="password">
        <div class="percent_container">
            <label for="percent">Процент</label>
            <input type="number" name="percent" placeholder="Процент" value="{{ $profile->percent ?? 0 }}" id="percent">
        </div>
        <button class="green_btn">Сохранить</button>
    </form>
@endsection

@push("scripts")
    <script>
        const selectElement = document.querySelector('#role');
        const percentContainer = document.querySelector(".percent_container");

        selectElement.addEventListener('change', (event) => {
            if(event.target.value === "dealer"){
                percentContainer.style.display = "block";
            }else{
                percentContainer.style.display = "none";
            }
        });

    </script>
@endpush
