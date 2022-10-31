<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guest();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1'],
            'email' => ['required', 'email:dns', 'unique:users'],
            // dns - проверка на существование доменной зоны
            'password' => ['required', 'confirmed', Password::default()],
            //Password::default() - класс по валидации поролей, метод по дефолту, это позволит задать правила валидации на уровне сервис-провайдара
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            // обработка email перед сохранением
            'email' => str(request('email'))
                ->squish()
                ->lower()
                ->value()
        ]);
    }
}
