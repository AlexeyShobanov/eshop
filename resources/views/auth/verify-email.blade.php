@extends('layout.auth')

@section('title', 'Подтвердите e-mail')
@section('content')

    {{--    @dump($errors)--}}

    <x-forms.auth-forms
        title="Подтвердите e-mail"
        action="{{ route('verification.send') }}"
        method="POST"
    >
        @csrf

        <p>
            Войдите в указанную при регистарции эл. почту и подтвердите свой e-mail. Если нет сообщения нажмите на
            кнопку
            для повторной отправки.
        </p>
        <x-forms.primary-button>
            Отправить повторно
        </x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.auth-forms>
@endsection
