<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class ConceptoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => 'required|in:1,2',
            'nombre' => 'required|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'Seleccione el tipo de concepto.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'nombre.required' => 'Ingrese el nombre del concepto.',
        ];
    }
}