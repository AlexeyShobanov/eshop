<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInFormRequest extends FormRequest
{
    public function authorize(): bool
    {
//        здесь определяем правила для кого доступен этот реквест
        return auth()->guest(); // только для гостей
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:dns'], // dns - проверка на существование доменной зоны
            'password' => ['required'],
        ];
    }
}
