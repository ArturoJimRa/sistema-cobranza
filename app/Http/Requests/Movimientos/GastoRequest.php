<?php

namespace App\Http\Requests\Movimientos;

use App\Models\FormaPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GastoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $conCliente = $this->filled('cliente_id');

        return [
            'fecha' => ['required', 'date'],

            'inmobiliaria_id' => [
                'required',
                'exists:inmobiliarias,id',
            ],

            // Opcional: gasto de un cliente (si no, es general de la plaza)
            'cliente_id' => [
                'nullable',
                Rule::exists('clientes', 'id')->where(
                    fn ($query) => $query->where('estado', 1)
                ),
            ],

            'concepto_id' => [
                'required',
                Rule::exists('conceptos', 'id')->where(
                    fn ($query) => $query
                        ->where('tipo', 2)
                        ->where('estado', 1)
                ),
            ],

            'forma_pago_id' => [
                'required',
                'exists:formas_pago,id',
            ],

            'cuenta_bancaria_id' => [
                'nullable',
                Rule::exists('cuentas_bancarias', 'id')->where(
                    fn ($query) => $query->where('estado', 1)
                ),
            ],

            'importe' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],

            'referencia' => [
                'nullable',
                'string',
                'max:150',
            ],

            // Obligatorias cuando el gasto se asigna a un cliente
            'observaciones' => [
                $conCliente ? 'required' : 'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $formaPago = FormaPago::find($this->forma_pago_id);

            if (!$formaPago) {
                return;
            }

            $esTransferencia = str_contains(
                mb_strtolower($formaPago->nombre),
                'transferencia'
            );

            if ($esTransferencia && !$this->cuenta_bancaria_id) {
                $validator->errors()->add(
                    'cuenta_bancaria_id',
                    'Seleccione la cuenta bancaria utilizada para el gasto.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'inmobiliaria_id.required' => 'Seleccione una inmobiliaria.',
            'cliente_id.exists' => 'Seleccione un cliente activo.',
            'observaciones.required' => 'Capture una observación: es obligatoria cuando el gasto se asigna a un cliente.',
            'concepto_id.required' => 'Seleccione el concepto del gasto.',
            'concepto_id.exists' => 'Seleccione un concepto de gasto válido.',
            'forma_pago_id.required' => 'Seleccione una forma de pago.',
            'importe.required' => 'Capture el importe.',
            'importe.numeric' => 'El importe debe ser numérico.',
            'importe.min' => 'El importe debe ser mayor a cero.',
        ];
    }
}