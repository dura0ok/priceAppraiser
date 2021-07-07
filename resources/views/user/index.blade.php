@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Пользователи</h1>
    <h1 class="section_name"><a href="{{ route("users.create") }}">Создать пользователя</a></h1>
    <div class="users-container">
        <div class="users">
            @foreach($users as $profile)
                <div class="user">
                    <h1>Имя: {{ $profile->name }}</h1>
                    <h2>Почта: {{ $profile->email ?? "отсутствует" }}</h2>
                    <h2>Телефон {{ $profile->phone ?? "отсутствует" }}</h2>
                    @isset($profile->percent)
                        <h3>Процент: {{ $profile->percent }}%</h3>
                    @endisset
                    <div class="buttons">
                        <a href="{{ route("users.edit", $profile->id) }}" class="edit">Редактировать</a>
                        <form action="{{ route("users.destroy", $profile->id) }}" method="POST" id="productForm">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
