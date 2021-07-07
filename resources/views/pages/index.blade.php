@extends("layouts.base.template")

@section("content")
    <h1 style="text-align: center;">Привет, {{ $user->name ?? "undefined" }}</h1>
    <h1 style="text-align: center;">Ваша роль: {{ $user->getRole() }}</h1>
@endsection
