<?php

namespace App\Http\Requests\Movimientos;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fecha' => 'required|date',
            'naturaleza' => 'required|in:1,2',
            'inmobiliaria_id' => 'required|exists:inmobiliarias,id',
            'tipo_inmueble_id' => 'nullable|exists:tipos_inmueble,id',
            'inmueble_id' => 'required|exists:inmuebles,id',
            'unidad_id' => 'nullable|exists:unidades,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'forma_pago_id' => 'required|exists:formas_pago,id',
            'cuenta_bancaria_id' => 'nullable|exists:cuentas_bancarias,id',
            'importe' => 'required|numeric|min:0.01',
            'referencia' => 'nullable|max:150',
            'observaciones' => 'nullable',
        ];
    }
}