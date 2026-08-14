<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class CuentaBancariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'banco_id' => 'required|exists:bancos,id',
            'nombre' => 'required|max:150',
            'numero_cuenta' => 'nullable|max:50',
            'clabe' => 'nullable|max:18',
        ];
    }

    public function messages(): array
    {
        return [
            'banco_id.required' => 'Seleccione un banco.',
            'banco_id.exists' => 'El banco seleccionado no es válido.',
            'nombre.required' => 'Ingrese el nombre de la cuenta.',
        ];
    }
}