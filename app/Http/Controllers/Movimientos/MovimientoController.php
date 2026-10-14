<?php

namespace App\Http\Controllers\Movimientos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movimientos\GastoRequest;
use App\Http\Requests\Movimientos\IngresoRequest;
use App\Http\Requests\Movimientos\MovimientoRequest;
use App\Models\BitacoraMovimiento;
use App\Models\Concepto;
use App\Models\CuentaBancaria;
use App\Models\FormaPago;
use App\Models\Inmobiliaria;
use App\Models\Inmueble;
use App\Models\Movimiento;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $movimientos = Movimiento::with([
                'inmobiliaria',
                'inmueble',
                'unidad',
                'concepto',
                'formaPago',
                'cuentaBancaria.banco',
            ])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = trim($request->buscar);

                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('folio', 'LIKE', "%{$buscar}%")
                        ->orWhere('referencia', 'LIKE', "%{$buscar}%")
                        ->orWhereHas('inmobiliaria', function ($q) use ($buscar) {
                            $q->where('nombre', 'LIKE', "%{$buscar}%");
                        })
                        ->orWhereHas('inmueble', function ($q) use ($buscar) {
                            $q->where('nombre', 'LIKE', "%{$buscar}%");
                        })
                        ->orWhereHas('unidad', function ($q) use ($buscar) {
                            $q->where('nombre', 'LIKE', "%{$buscar}%")
                                ->orWhere('razon_social', 'LIKE', "%{$buscar}%");
                        })
                        ->orWhereHas('concepto', function ($q) use ($buscar) {
                            $q->where('nombre', 'LIKE', "%{$buscar}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('movimientos.index', compact('movimientos'));
    }

    public function createIngreso()
    {
        return view('movimientos.ingresos.create', [
            'inmobiliarias' => Inmobiliaria::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'conceptos' => Concepto::where('estado', 1)
                ->where('tipo', 1)
                ->orderBy('nombre')
                ->get(),

            'formasPago' => FormaPago::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'cuentas' => CuentaBancaria::with('banco')
                ->where('estado', 1)
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function storeIngreso(IngresoRequest $request)
    {
        $movimiento = DB::transaction(function () use ($request) {

            $inmueble = Inmueble::where('estado', 1)
                ->where('inmobiliaria_id', $request->inmobiliaria_id)
                ->findOrFail($request->inmueble_id);

            /*
             * Si el ingreso pertenece a un local,
             * obtenemos automáticamente el cliente
             * asignado a ese local.
             */
            $clienteId = null;

            if ($request->filled('unidad_id')) {

                $unidad = Unidad::where('estado', 1)
                    ->where('inmueble_id', $request->inmueble_id)
                    ->whereNotNull('cliente_id')
                    ->findOrFail($request->unidad_id);

                $clienteId = $unidad->cliente_id;
            }

            $movimiento = Movimiento::create([
                'folio' => $this->generarFolio(),

                'fecha' => $request->fecha,

                // 1 = Ingreso
                'naturaleza' => 1,

                /*
                 * Cliente dueño del local.
                 */
                'cliente_id' => $clienteId,

                'inmobiliaria_id' => $request->inmobiliaria_id,

                'tipo_inmueble_id' => $inmueble->tipo_inmueble_id,

                'inmueble_id' => $inmueble->id,

                'unidad_id' => $request->filled('unidad_id')
                    ? $request->unidad_id
                    : null,

                'concepto_id' => $request->concepto_id,

                'forma_pago_id' => $request->forma_pago_id,

                'cuenta_bancaria_id' => $request->filled('cuenta_bancaria_id')
                    ? $request->cuenta_bancaria_id
                    : null,

                'importe' => $request->importe,

                'referencia' => $this->textoMayusculas(
                    $request->referencia
                ),

                'observaciones' => $this->textoMayusculas(
                    $request->observaciones
                ),

                'usuario_id' => Auth::id(),

                'estado' => 1,
            ]);

            BitacoraMovimiento::create([
                'movimiento_id' => $movimiento->id,
                'usuario_id' => Auth::id(),
                'accion' => 'CREACIÓN',
                'descripcion' => 'Se registró un ingreso.',
            ]);

            return $movimiento;
        });

        return redirect()
            ->route('movimientos.index')
            ->with(
                'success',
                "Ingreso {$movimiento->folio} registrado correctamente."
            );
    }

    public function createGasto()
    {
        return view('movimientos.gastos.create', [
            'inmobiliarias' => Inmobiliaria::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'conceptos' => Concepto::where('estado', 1)
                ->where('tipo', 2)
                ->orderBy('nombre')
                ->get(),

            'formasPago' => FormaPago::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'cuentas' => CuentaBancaria::with('banco')
                ->where('estado', 1)
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function storeGasto(GastoRequest $request)
    {
        $movimiento = DB::transaction(function () use ($request) {

            $inmueble = Inmueble::where('estado', 1)
                ->where('inmobiliaria_id', $request->inmobiliaria_id)
                ->findOrFail($request->inmueble_id);

            $movimiento = Movimiento::create([
                'folio' => $this->generarFolio(),
                'fecha' => $request->fecha,

                // 2 = Gasto
                'naturaleza' => 2,

                /*
                 * Los gastos no pertenecen a un cliente.
                 */
                'cliente_id' => null,

                'inmobiliaria_id' => $request->inmobiliaria_id,

                'tipo_inmueble_id' => $inmueble->tipo_inmueble_id,

                'inmueble_id' => $inmueble->id,

                'unidad_id' => null,

                'concepto_id' => $request->concepto_id,

                'forma_pago_id' => $request->forma_pago_id,

                'cuenta_bancaria_id' => $request->filled('cuenta_bancaria_id')
                    ? $request->cuenta_bancaria_id
                    : null,

                'importe' => $request->importe,

                'referencia' => $this->textoMayusculas(
                    $request->referencia
                ),

                'observaciones' => $this->textoMayusculas(
                    $request->observaciones
                ),

                'usuario_id' => Auth::id(),

                'estado' => 1,
            ]);

            BitacoraMovimiento::create([
                'movimiento_id' => $movimiento->id,
                'usuario_id' => Auth::id(),
                'accion' => 'CREACIÓN',
                'descripcion' => 'Se registró un gasto.',
            ]);

            return $movimiento;
        });

        return redirect()
            ->route('movimientos.index')
            ->with(
                'success',
                "Gasto {$movimiento->folio} registrado correctamente."
            );
    }

    public function edit(Movimiento $movimiento)
    {
        if ($movimiento->estado == 2) {
            return redirect()
                ->route('movimientos.index')
                ->with(
                    'error',
                    'No se puede editar un movimiento cancelado.'
                );
        }

        $inmobiliarias = Inmobiliaria::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $inmuebles = Inmueble::where('estado', 1)
            ->where('inmobiliaria_id', $movimiento->inmobiliaria_id)
            ->orderBy('nombre')
            ->get();

        $unidades = Unidad::where('estado', 1)
            ->where('inmueble_id', $movimiento->inmueble_id)
            ->orderBy('nombre')
            ->get();

        $conceptos = Concepto::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $formasPago = FormaPago::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        $cuentas = CuentaBancaria::with('banco')
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('movimientos.edit', compact(
            'movimiento',
            'inmobiliarias',
            'inmuebles',
            'unidades',
            'conceptos',
            'formasPago',
            'cuentas'
        ));
    }

    public function update(
        MovimientoRequest $request,
        Movimiento $movimiento
    ) {
        if ($movimiento->estado == 2) {
            return back()->with(
                'error',
                'No se puede editar un movimiento cancelado.'
            );
        }

        DB::transaction(function () use ($request, $movimiento) {

            $inmueble = Inmueble::findOrFail(
                $request->inmueble_id
            );

            /*
             * Si el movimiento tiene un local,
             * actualizamos también el cliente asociado.
             */
            $clienteId = null;

            if ($request->filled('unidad_id')) {

                $unidad = Unidad::where('estado', 1)
                    ->where('inmueble_id', $request->inmueble_id)
                    ->whereNotNull('cliente_id')
                    ->findOrFail($request->unidad_id);

                $clienteId = $unidad->cliente_id;
            }

            $movimiento->update([
                'fecha' => $request->fecha,

                'naturaleza' => $request->naturaleza,

                'cliente_id' => $clienteId,

                'inmobiliaria_id' => $request->inmobiliaria_id,

                'tipo_inmueble_id' => $inmueble->tipo_inmueble_id,

                'inmueble_id' => $inmueble->id,

                'unidad_id' => $request->filled('unidad_id')
                    ? $request->unidad_id
                    : null,

                'concepto_id' => $request->concepto_id,

                'forma_pago_id' => $request->forma_pago_id,

                'cuenta_bancaria_id' => $request->filled('cuenta_bancaria_id')
                    ? $request->cuenta_bancaria_id
                    : null,

                'importe' => $request->importe,

                'referencia' => $this->textoMayusculas(
                    $request->referencia
                ),

                'observaciones' => $this->textoMayusculas(
                    $request->observaciones
                ),
            ]);

            BitacoraMovimiento::create([
                'movimiento_id' => $movimiento->id,
                'usuario_id' => Auth::id(),
                'accion' => 'EDICIÓN',
                'descripcion' => 'Se modificó el movimiento.',
            ]);
        });

        return redirect()
            ->route('movimientos.index')
            ->with(
                'success',
                'Movimiento actualizado correctamente.'
            );
    }

    public function destroy(Movimiento $movimiento)
    {
        if ($movimiento->estado == 2) {
            return back()->with(
                'error',
                'El movimiento ya se encuentra cancelado.'
            );
        }

        DB::transaction(function () use ($movimiento) {

            $movimiento->update([
                'estado' => 2,
                'cancelado_en' => now(),
                'cancelado_por' => Auth::id(),
            ]);

            BitacoraMovimiento::create([
                'movimiento_id' => $movimiento->id,
                'usuario_id' => Auth::id(),
                'accion' => 'CANCELACIÓN',
                'descripcion' => 'Se canceló el movimiento.',
            ]);
        });

        return back()->with(
            'success',
            'Movimiento cancelado correctamente.'
        );
    }

    private function generarFolio(): string
    {
        $ultimoMovimiento = Movimiento::query()
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $numero = $ultimoMovimiento
            ? $ultimoMovimiento->id + 1
            : 1;

        return 'MOV-'
            . now()->format('Y')
            . '-'
            . str_pad(
                (string) $numero,
                6,
                '0',
                STR_PAD_LEFT
            );
    }

    private function textoMayusculas(?string $texto): ?string
    {
        if ($texto === null || trim($texto) === '') {
            return null;
        }

        return Str::upper(trim($texto));
    }
}