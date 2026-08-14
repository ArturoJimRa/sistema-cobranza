<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class BancoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingrese el nombre del banco.',
        ];
    }
}