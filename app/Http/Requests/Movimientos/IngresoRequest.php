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

    /**
     * El select de cliente trae la opción "sin" (ingreso general sin cliente).
     */
    protected function prepareForValidation(): void
    {
        $sinCliente = $this->input('cliente_id') === 'sin';

        $this->merge([
            'origen' => $sinCliente ? 'general' : 'cliente',
            'cliente_id' => $sinCliente ? null : $this->input('cliente_id'),
        ]);
    }

    public function rules(): array
    {
        $porCliente = $this->input('origen') === 'cliente';

        $ubicacion = $porCliente
            ? [
                // Pago de un cliente con al menos un local activo en la inmobiliaria
                'cliente_id' => [
                    'required',
                    Rule::exists('clientes', 'id')->where(
                        fn ($query) => $query->where('estado', 1)
                    ),
                    Rule::exists('unidades', 'cliente_id')->where(
                        fn ($query) => $query
                            ->where('estado', 1)
                            ->whereIn('inmueble_id', fn ($sub) => $sub
                                ->select('id')
                                ->from('inmuebles')
                                ->where('inmobiliaria_id', $this->inmobiliaria_id))
                    ),
                ],
            ]
            : [];

        return $ubicacion + [
            'origen' => ['required', Rule::in(['cliente', 'general'])],

            'fecha' => ['required', 'date'],

            'inmobiliaria_id' => [
                'required',
                'exists:inmobiliarias,id',
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

            'facturado' => ['nullable', 'boolean'],

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

            'cliente_id.required' => 'Seleccione el cliente que realizó el pago.',

            'cliente_id.exists' => 'Seleccione un cliente activo con locales en la inmobiliaria elegida.',

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