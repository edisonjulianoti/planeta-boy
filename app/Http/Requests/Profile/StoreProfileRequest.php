<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'age'                => ['required', 'integer', 'min:18', 'max:100'],
            'gender'             => ['nullable', 'string', 'in:masculino,feminino,nao_binario,outro'],
            'city'               => ['required', 'string', 'max:255'],
            'state'              => ['required', 'string', 'size:2'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'services'           => ['nullable', 'array'],
            'services.*'         => ['integer', 'exists:services,id'],
            'gallery'            => ['nullable', 'array', 'max:10'],
            'gallery.*'          => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'video_files'        => ['nullable', 'array', 'max:5'],
            'video_files.*'      => ['file', 'mimes:mp4,webm', 'max:51200'],
            'remove_images'      => ['nullable', 'array'],
            'remove_images.*'    => ['integer', 'exists:profile_images,id'],
            'existing_images'    => ['nullable', 'array'],
            'existing_images.*'  => ['integer', 'exists:profile_images,id'],
            'main_image_id'      => ['nullable', 'integer', 'exists:profile_images,id'],
            'new_main_image_index' => ['nullable', 'integer', 'min:0'],
            'order'              => ['nullable', 'array'],
            'order.*'            => ['integer', 'min:0'],

            // Características físicas
            'height'             => ['nullable', 'integer', 'min:100', 'max:250'],
            'weight'             => ['nullable', 'integer', 'min:30', 'max:300'],
            'hair_color'         => ['nullable', 'string', 'max:100'],
            'eye_color'          => ['nullable', 'string', 'max:100'],
            'ethnicity'          => ['nullable', 'string', 'max:100'],
            'body_type'          => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Informe o nome do perfil.',
            'age.required'   => 'Informe a idade.',
            'age.min'        => 'Idade mínima é 18 anos.',
            'age.max'        => 'Idade máxima é 100 anos.',
            'city.required'  => 'Informe a cidade.',
            'state.required' => 'Informe o estado (UF).',
            'state.size'     => 'Estado deve ter 2 caracteres (UF).',
            'gallery.*.image'        => 'Cada arquivo deve ser uma imagem.',
            'gallery.*.max'          => 'Cada imagem deve ter no máximo 5MB.',
            'video_files.array'       => 'Formato inválido para vídeos.',
            'video_files.*.file'     => 'Cada arquivo de vídeo deve ser um arquivo válido.',
            'video_files.*.mimes'    => 'Cada vídeo deve ser MP4 ou WebM.',
            'video_files.*.max'      => 'Cada vídeo deve ter no máximo 50MB.',
            'height.integer' => 'A altura deve ser um valor numérico.',
            'height.min'     => 'Altura mínima é 100cm.',
            'height.max'     => 'Altura máxima é 250cm.',
            'weight.integer' => 'O peso deve ser um valor numérico.',
            'weight.min'     => 'Peso mínimo é 30kg.',
            'weight.max'     => 'Peso máximo é 300kg.',
        ];
    }
}
