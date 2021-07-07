@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Редактирование продукта</h1>
    <form class="product-form" action="{{ route("products.update", $product->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        {{ method_field('PUT') }}
        @include("layouts.base.parts.errors")
        <label for="catalog">Каталог</label>
        <select name="catalogs[]" id="catalog" multiple>
            @foreach($catalogs as $catalog)
                <option value="{{ $catalog->id }}" @if($catalog->own) selected @endif>{{ $catalog->name }}</option>
            @endforeach
        </select>
        <label for="articul">Артикул</label>
        <input type="text" name="articul" placeholder="Артикул" value="{{ $product->articul ?? "" }}" id="articul">
        <label for="name">Имя</label>
        <input type="text" name="name" placeholder="Имя продукта" value="{{ $product->name ?? "" }}" id="name">
        <label for="description">Описание</label>
        <textarea name="description" id="description">{{ $product->description ?? "Описание продукта" }}</textarea>
        <label for="price">Цена</label>
        <input type="number" name="price" id="price" value="{{ $product->price }}">
        <label for="file">Вставьте новую картинку, если хотите обновить</label>
        <label for="file">В данный момент картинка @isset($product->image) <a href="{{ asset("storage/".$product->image) }}" target="_blank">есть</a> @else отсутствует @endisset</label>
        <input type="file" name="file" id="file">
        <button class="green_btn">Сохранить</button>
    </form>
@endsection
