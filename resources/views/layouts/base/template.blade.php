<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Расценщик</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<!-- Start Header -->
@include("layouts.base.parts.header")
<!-- End Header -->
<!-- Start content wrapper -->
<div class="content-wrapper">
    <!-- Start main content -->
    <main>
        @yield("content")
    </main>
    <!-- End main content -->
</div>
<!-- End content wrapper -->
<script src="{{ asset("js/app.js") }}"></script>
@stack('scripts')
</body>
</html>
