<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Mostrar lista de clientes.
     */
    public function index()
    {
        $clientes = Cliente::with([
                'unidades.inmueble',
                'movimientos' => function ($query) {
                    $query
                        ->where('naturaleza', 1)
                        ->where('estado', 1);
                }
            ])
            ->orderBy('nombre')
            ->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario para crear cliente.
     */
    public function create()
    {
        $unidades = Unidad::where('estado', 1)
            ->whereNull('cliente_id')
            ->with('inmueble')
            ->orderBy('nombre')
            ->get();

        return view('clientes.create', compact('unidades'));
    }

    /**
     * Guardar nuevo cliente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',

            /*
             * Saldo inicial del cliente.
             *
             * Positivo = debe
             * Negativo = saldo a favor
             */
            'saldo_inicial' => [
                'nullable',
                'numeric',
                'max:9999999999.99',
                'min:-9999999999.99',
            ],

            'estado' => 'nullable|boolean',

            'unidades' => 'nullable|array',
            'unidades.*' => 'integer|exists:unidades,id',
        ]);

        DB::transaction(function () use ($validated) {

            $saldoInicial = $validated['saldo_inicial'] ?? 0;

            $cliente = Cliente::create([
                'nombre' => $validated['nombre'],
                'razon_social' => $validated['razon_social'] ?? null,
                'rfc' => $validated['rfc'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'correo' => $validated['correo'] ?? null,
                'direccion' => $validated['direccion'] ?? null,

                /*
                 * Saldo con el que entra el cliente.
                 */
                'saldo_inicial' => $saldoInicial,

                /*
                 * Estos valores se conservarán
                 * para compatibilidad con la estructura actual.
                 */
                'saldo_favor' => 0,
                'saldo_vencido' => 0,

                'estado' => $validated['estado'] ?? true,
            ]);

            /*
             * Asignamos los locales seleccionados al cliente.
             */
            if (!empty($validated['unidades'])) {

                Unidad::whereIn('id', $validated['unidades'])
                    ->whereNull('cliente_id')
                    ->update([
                        'cliente_id' => $cliente->id
                    ]);
            }
        });

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Mostrar información de un cliente.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load([
            'unidades.inmueble',
            'movimientos' => function ($query) {
                $query
                    ->where('naturaleza', 1)
                    ->where('estado', 1)
                    ->with([
                        'unidad',
                        'concepto',
                        'formaPago'
                    ])
                    ->orderByDesc('fecha')
                    ->orderByDesc('id');
            }
        ]);

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar formulario para editar cliente.
     */
    public function edit(Cliente $cliente)
    {
        /*
         * Mostramos todos los locales activos.
         *
         * Incluimos:
         * - locales sin cliente
         * - locales que ya pertenecen a este cliente
         */
        $unidades = Unidad::where('estado', 1)
            ->where(function ($query) use ($cliente) {
                $query->whereNull('cliente_id')
                    ->orWhere('cliente_id', $cliente->id);
            })
            ->with('inmueble')
            ->orderBy('nombre')
            ->get();

        $cliente->load('unidades');

        return view('clientes.edit', compact(
            'cliente',
            'unidades'
        ));
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',

            /*
             * Permitimos modificar manualmente
             * el saldo inicial.
             */
            'saldo_inicial' => [
                'nullable',
                'numeric',
                'max:9999999999.99',
                'min:-9999999999.99',
            ],

            'estado' => 'nullable|boolean',

            'unidades' => 'nullable|array',
            'unidades.*' => 'integer|exists:unidades,id',
        ]);

        DB::transaction(function () use ($validated, $cliente) {

            $cliente->update([
                'nombre' => $validated['nombre'],
                'razon_social' => $validated['razon_social'] ?? null,
                'rfc' => $validated['rfc'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'correo' => $validated['correo'] ?? null,
                'direccion' => $validated['direccion'] ?? null,

                /*
                 * Saldo inicial del cliente.
                 */
                'saldo_inicial' => $validated['saldo_inicial'] ?? 0,

                /*
                 * No modificamos saldo_favor ni saldo_vencido.
                 * Estos serán determinados por el sistema.
                 */

                'estado' => $validated['estado'] ?? false,
            ]);

            /*
             * Quitamos las asignaciones actuales
             * de este cliente.
             */
            Unidad::where('cliente_id', $cliente->id)
                ->update([
                    'cliente_id' => null
                ]);

            /*
             * Asignamos los nuevos locales seleccionados.
             */
            if (!empty($validated['unidades'])) {

                Unidad::whereIn('id', $validated['unidades'])
                    ->whereNull('cliente_id')
                    ->update([
                        'cliente_id' => $cliente->id
                    ]);
            }
        });

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Desactivar cliente.
     */
    public function destroy(Cliente $cliente)
    {
        /*
         * No eliminamos físicamente el cliente.
         * Lo desactivamos para conservar su historial.
         */
        DB::transaction(function () use ($cliente) {

            $cliente->update([
                'estado' => false
            ]);

            /*
             * Los locales dejan de estar asignados
             * al cliente.
             */
            Unidad::where('cliente_id', $cliente->id)
                ->update([
                    'cliente_id' => null
                ]);
        });

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente desactivado correctamente.');
    }
}