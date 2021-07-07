@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Регистрация</h1>
    @include("layouts.base.parts.errors")
    <form class="register_form" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Имя">
        <input type="email" name="email" placeholder="E-mail">
        <input type="text" name="phone" placeholder="Телефон">
        <input type="password" name="password" placeholder="Пароль">
        <input type="password" name="password_confirmation" placeholder="Повторите пароль">
        <button class="green_btn">Отправить</button>
    </form>
@endsection
