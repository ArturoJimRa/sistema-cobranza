<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingrese el nombre de la forma de pago.',
        ];
    }
}