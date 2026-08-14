<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class InmobiliariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'        => 'required|max:150',
            'razon_social'  => 'nullable|max:255',
            'rfc'           => 'nullable|max:13',
            'telefono'      => 'nullable|max:20',
            'correo'        => 'nullable|email|max:150',
            'direccion'     => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.email'    => 'Ingrese un correo válido.'
        ];
    }
}