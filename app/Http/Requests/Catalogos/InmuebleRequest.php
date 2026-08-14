<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class InmuebleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inmobiliaria_id' => 'required|exists:inmobiliarias,id',
            'tipo_inmueble_id' => 'required|exists:tipos_inmueble,id',
            'nombre' => 'required|max:150',
            'direccion' => 'nullable',
            'observaciones' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'inmobiliaria_id.required' => 'Seleccione una inmobiliaria.',
            'tipo_inmueble_id.required' => 'Seleccione el tipo de inmueble.',
            'nombre.required' => 'Ingrese el nombre del inmueble.',
        ];
    }
}