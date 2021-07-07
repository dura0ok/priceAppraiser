@extends("layouts.base.template")

@section("content")
    <div class="products-list">
        @foreach($products as $product)
        <div class="product">
            <a href="" class="naming">{{ $product->articul }} || {{ $product->name }}</a>
            @isset($product->image)
                <img src="{{ asset("storage/".$product->image) }}">
            @endisset
            <p class="price">Стартовая цена: {{ $product->price }}</p>
            <hr>
            <p class="description">{{ $product->description }}</p>
            <hr>
            <div class="buttons">
                <a href="{{ route("products.edit", $product->id) }}" class="edit">Редактировать</a>
                <form action="{{ route("products.destroy", $product) }}" method="POST" id="productForm">
                    @csrf
                    @method('DELETE')
                    <button class="delete" type="submit">Удалить</button>
                </form>

            </div>
        </div>
        @endforeach
    </div>
@endsection
