@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Импорт продуктов</h1>
    <form action="{{ route("products.import") }}" class="upload_form" method="POST" enctype="multipart/form-data">
        @csrf
        @include("layouts.base.parts.errors")
        <label for="catalog">Каталог</label>
        <select name="catalog_id" id="catalog">
            @foreach($catalogs as $catalog)
                <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
            @endforeach
        </select>
        <label for="columns">Введите через ";" названия колонок где лежит артикул,имя,описание,цена</label>
        <input type="text" name="columns" placeholder="Артикул;Имя;Описание;Цена" id="columns">
        <input type="file" name="file">
        <button class="green_btn">Отправить</button>
    </form>
@endsection

