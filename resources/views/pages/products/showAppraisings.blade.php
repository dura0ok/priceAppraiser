@extends("layouts.base.template")

@section("content")
    <div class="appraisings">
        @foreach($appraisings as $appraising)
            <div class="appraising">
                <h1>Заявка #{{ $appraising->id }}</h1>
                <h2>Телефон: {{ $appraising->user->phone }}</h2>
                <h3>Email: <a href="mailto:{{ $appraising->user->email }}">{{ $appraising->user->email }}</a></h3>
                <h4>Дата добавления: {{ $appraising->created_at }}</h4>
                <a href="{{ asset("storage/".$appraising->file) }}" download="">Скачать расцененную заявку</a>
                <form action="{{ route("products.deleteAppraising", $appraising->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="delete">Удалить</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
