<?php

namespace App\Http\Requests\Movimientos;

use App\Models\FormaPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngresoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date'],

            'inmobiliaria_id' => [
                'required',
                'exists:inmobiliarias,id',
            ],

            'inmueble_id' => [
                'required',
                Rule::exists('inmuebles', 'id')->where(
                    fn ($query) => $query
                        ->where('inmobiliaria_id', $this->inmobiliaria_id)
                        ->where('estado', 1)
                ),
            ],

            'unidad_id' => [
                'nullable',
                Rule::exists('unidades', 'id')->where(
                    fn ($query) => $query
                        ->where('inmueble_id', $this->inmueble_id)
                        ->where('estado', 1)
                        ->whereNotNull('cliente_id')
                ),
            ],

            'concepto_id' => [
                'required',
                Rule::exists('conceptos', 'id')->where(
                    fn ($query) => $query
                        ->where('tipo', 1)
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

            'observaciones' => [
                'nullable',
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
                    'Seleccione la cuenta bancaria donde se recibió la transferencia.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',

            'inmobiliaria_id.required' => 'Seleccione una inmobiliaria.',

            'inmueble_id.required' => 'Seleccione un inmueble.',

            'inmueble_id.exists' =>
                'El inmueble no pertenece a la inmobiliaria seleccionada.',

            'unidad_id.exists' =>
                'El local seleccionado no pertenece al inmueble o no tiene un cliente asignado.',

            'concepto_id.required' =>
                'Seleccione el concepto del ingreso.',

            'concepto_id.exists' =>
                'Seleccione un concepto de ingreso válido.',

            'forma_pago_id.required' =>
                'Seleccione una forma de pago.',

            'importe.required' =>
                'Capture el importe.',

            'importe.numeric' =>
                'El importe debe ser numérico.',

            'importe.min' =>
                'El importe debe ser mayor a cero.',
        ];
    }
}