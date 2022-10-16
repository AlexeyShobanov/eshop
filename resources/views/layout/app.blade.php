<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title>@yield('title')</title>

    <meta name="description" content="Интернет-магазин eShop">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
    <link rel="mask-icon" href="./safari-pinned-tab.svg" color="#1E1F43">
    <meta name="msapplication-TileColor" content="#1E1F43">
    <meta name="theme-color" content="#1E1F43">

    {{--    <link rel="stylesheet" href="./css/tailwind.css">--}}
    {{--    <link rel="stylesheet" href="./css/main.css">--}}
    @vite(['resources/css/app.css','resources/sass/main.sass','resources/js/app.js'])
</head>
<body x-data="{ 'showTaskUploadModal': false, 'showTaskEditModal': false }" x-cloak>

@yield('content')

@include('../parts/mobile-menu')
@include('../parts/modals')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
{{--<script src="./js/app.js"></script>--}}
</body>
</html>
