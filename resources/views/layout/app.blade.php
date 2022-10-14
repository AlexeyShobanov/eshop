<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    @vite(['resources/css/app.css','resources/sass/main.sass','resources/js/app.js'])
    <title>@yield('title')</title>
</head>
<body class="antialiased">
<div id="content" class="d-flex">
    @yield('content')
</div>
</body>
</html>
