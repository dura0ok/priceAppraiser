@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Создание продукта</h1>
    <form class="product-form" action="{{ route("products.store") }}" enctype="multipart/form-data" method="POST">
        @csrf
        @include("layouts.base.parts.errors")
        <label for="catalog">Каталог</label>
        <select name="catalogs[]" id="catalog" multiple>
            @foreach($catalogs as $catalog)
                <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
            @endforeach
        </select>
        <label for="articul">Артикул</label>
        <input type="text" name="articul" placeholder="Артикул" id="articul">
        <label for="name">Имя</label>
        <input type="text" name="name" placeholder="Имя продукта" id="name">
        <label for="description">Описание</label>
        <textarea name="description" id="description"></textarea>
        <label for="price">Цена</label>
        <input type="number" name="price" id="price">
        <label for="file">Картинка</label>
        <input type="file" name="file" id="file">
        <button class="green_btn">Создать</button>
    </form>
@endsection
