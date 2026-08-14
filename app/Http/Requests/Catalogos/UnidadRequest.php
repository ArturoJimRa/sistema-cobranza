<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class UnidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inmueble_id' => 'required|exists:inmuebles,id',
            'nombre' => 'required|max:150',
            'razon_social' => 'nullable|max:255',
        ];
    }
}