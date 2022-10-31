@extends('layout.auth')

@section('title', 'Вход в аккаунт')
@section('content')
    <x-forms.auth-forms
        title="Вход в аккаунт"
        action="{{ route('signIn') }}"
        method="POST"
    >
        @csrf

        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            required="true"
            value="{{ old('email') }}"
            :isError="$errors->has('email')"
        ></x-forms.text-input>
        @error('email')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror
        <x-forms.text-input
            name="password"
            type="password"
            placeholder="Пароль"
            required="true"
            :isError="$errors->has('email')"
        ></x-forms.text-input>

        <x-forms.primary-button>
            Войти
        </x-forms.primary-button>

        <x-slot:socialAuth>
            <ul class="space-y-3 my-3">
                <li>
                    <x-forms.github-button></x-forms.github-button>
                </li>
            </ul>
        </x-slot:socialAuth>

        <x-slot:buttons>
            <div class="space-y-3 mt-5">
                <div class="text-xxs md:text-xs"><a href="{{ route('password.request') }}"
                                                    class="text-white hover:text-white/70 font-bold">Забыли пароль?</a>
                </div>
                <div class="text-xxs md:text-xs"><a href="{{ route('signUp') }}"
                                                    class="text-white hover:text-white/70 font-bold">Регистрация</a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-forms>
@endsection