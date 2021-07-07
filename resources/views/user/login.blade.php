@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Авторизация</h1>
    @include("layouts.base.parts.errors")
    <form class="login_form" method="POST">
        @csrf
        <input type="email" name="email" placeholder="E-mail">
        <input type="password" name="password" placeholder="Пароль">
        <button class="green_btn">Отправить</button>
    </form>
@endsection
