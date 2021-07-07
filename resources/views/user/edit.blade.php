@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Редактирование пользователя</h1>
    <form class="product-form" action="{{ route("users.update", $profile->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        {{ method_field('PUT') }}
        @include("layouts.base.parts.errors")
        <label for="role">Роль</label>
        <select name="role" id="role">
            @foreach($roles as $role)
                <option value="{{ $role }}" @if($role == $profile->role) selected @endif>{{ $role }}</option>
            @endforeach
        </select>

        <label for="name">Имя</label>
        <input type="text" name="name" placeholder="Имя" value="{{ $profile->name ?? "" }}" id="name">
        <label for="email">Почта</label>
        <input type="email" name="email" placeholder="Почта" value="{{ $profile->email ?? "" }}" id="email">
        <label for="phone">Телефон</label>
        <input type="text" name="phone" placeholder="Телефон" value="{{ $profile->phone ?? "" }}" id="phone">
        @if($profile->role == "dealer")
            <label for="percent">Процент</label>
            <input type="number" name="percent" placeholder="Процент" value="{{ $profile->percent ?? 0 }}" id="percent">
        @endif
        <button class="green_btn">Сохранить</button>
    </form>
@endsection
