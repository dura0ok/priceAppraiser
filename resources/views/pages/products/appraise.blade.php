@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Расценить заявку</h1>
    <form action="{{ route("products.appraise") }}" class="upload_form" method="POST" enctype="multipart/form-data">
        @csrf
        @include("layouts.base.parts.errors")
        <label for="articulColumn">Колонка где артикулы</label>
        <input type="text" name="articulColumn" id="articulColumn">
        <label for="counterColumn">Колонка где количество</label>
        <input name="counterColumn" id="counterColumn">
        @if($user->role == "admin")
            <label for="percent">Процент на сколько поднять/убавить (-5) или (+5)</label>
            <input type="number" name="percent" id="percent">
        @endif
        <label for="file">Сама заявка</label>
        <input type="file" name="file" id="file">
        <button class="green_btn">Расценить</button>
    </form>
@endsection
