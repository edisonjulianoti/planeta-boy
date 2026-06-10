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
            'gallery'            => ['nullable', 'array', 'max:10'],
            'gallery.*'          => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'video_url'          => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)/'],
            'video_file'         => ['nullable', 'file', 'mimes:mp4,webm', 'max:51200'],
            'remove_images'      => ['nullable', 'array'],
            'remove_images.*'    => ['integer', 'exists:profile_images,id'],
            'existing_images'    => ['nullable', 'array'],
            'existing_images.*'  => ['integer', 'exists:profile_images,id'],
            'main_image_id'      => ['nullable', 'integer', 'exists:profile_images,id'],
            'new_main_image_index' => ['nullable', 'integer', 'min:0'],
            'order'              => ['nullable', 'array'],
            'order.*'            => ['integer', 'min:0'],
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
            'video_url.url'          => 'URL do vídeo inválida.',
            'video_file.required'    => 'Selecione um arquivo de vídeo.',
            'video_file.file'        => 'O arquivo enviado não é um vídeo válido.',
            'video_file.mimes'       => 'O vídeo deve ser do formato MP4 ou WebM.',
            'video_file.max'         => 'O vídeo deve ter no máximo 50MB.',
        ];
    }
}
