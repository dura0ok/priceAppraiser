@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Поиск по артикулу или наименованию</h1>
    <form action="{{ route("products.find") }}" class="search" method="POST">
        @csrf
        <input type="articul" name="field">
        <button class="green_btn">Найти</button>
    </form>
@endsection
