<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="description" content="Интернет-магазин eShop">

    <title>404</title>

    @vite(['resources/css/app.css','resources/sass/main.sass','resources/js/app.js'])
<body>
<main class="md:min-h-screen md:flex md:items-center md:justify-center py-16 lg:py-20">
    <div class="container">

        <h1 class="text-2xl font-black text-center">Ошибка 404</h1>
        <p class="max-w-[720px] mx-auto mt-4 text-body text-center">Похоже, что данная страница не найдена, вы можете
            вернуться на главную страницу.</p>
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="btn btn-pink" rel="home">Вернутся на главную</a>
        </div>

    </div>
</main>
</body>
</html>
