<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'cpf'   => ['nullable', 'string', 'max:14', new Cpf],
            'bio'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Informe seu nome.',
            'email.required' => 'Informe o e-mail.',
            'email.email'    => 'Digite um e-mail valido.',
            'cpf.max'        => 'CPF invalido.',
        ];
    }
}
