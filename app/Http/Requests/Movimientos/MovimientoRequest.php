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
            'inmueble_id' => 'nullable|exists:inmuebles,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'unidad_id' => 'nullable|exists:unidades,id',
            'concepto_id' => 'required|exists:conceptos,id',
            'forma_pago_id' => 'required|exists:formas_pago,id',
            'cuenta_bancaria_id' => 'nullable|exists:cuentas_bancarias,id',
            'importe' => 'required|numeric|min:0.01',
            'facturado' => 'nullable|boolean',
            'referencia' => 'nullable|max:150',
            // Gasto asignado a un cliente: observación obligatoria
            'observaciones' => ((int) $this->naturaleza === 2 && $this->filled('cliente_id')) ? 'required' : 'nullable',
        ];
    }
}