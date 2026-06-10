<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

final class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:1000'],
            'rating'  => ['nullable', 'decimal:0,2', 'min:0', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Escreva um comentário.',
            'rating.max'       => 'A avaliação máxima é 5.',
        ];
    }
}
