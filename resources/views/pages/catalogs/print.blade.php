@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Скачать каталог/каталоги</h1>
    <form action="{{ route("catalogs.print") }}" class="print_form" method="POST">
        @csrf
        @include("layouts.base.parts.errors")
        <label for="catalog">Выберите какой каталог хотите распечатать</label>
        <select name="catalog_id" id="catalog">
            <option value="all">Все</option>
            @foreach($catalogs as $catalog)
                <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
            @endforeach
        </select>
        <div class="radio">
            <div class="item">
            <label for="art_sort">Сортировка по артикулу</label>
            <input type="radio" name="sort" value="articul" id="art_sort">
            </div>
            <div class="item">
            <label for="name_sort">Сортировка по наименованию</label>
            <input type="radio" name="sort" value="name" id="name_sort">
            </div>
        </div>
        <label for="percent">Процент на сколько поднять/убавить (-5) или (+5)</label>
        <input type="number" name="percent" id="percent">
        <button class="green_btn">Сохранить файл</button>
    </form>
@endsection

