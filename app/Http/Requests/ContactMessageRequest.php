<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Informe seu nome.',
            'email.required'   => 'Informe o e-mail.',
            'email.email'      => 'Digite um e-mail válido.',
            'subject.required' => 'Informe o assunto.',
            'message.required' => 'Escreva sua mensagem.',
        ];
    }
}
