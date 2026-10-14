<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Concepto;
use App\Models\FormaPago;
use App\Models\Inmobiliaria;
use App\Models\Inmueble;
use App\Models\Movimiento;
use App\Models\Unidad;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VISTA PRINCIPAL DE REPORTES
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $movimientos = $this->filtrar($request)
            ->paginate(20);

        $clienteSeleccionado = null;

        if ($request->cliente_id) {

            $clienteSeleccionado = Cliente::find($request->cliente_id);
        }

        return view('reportes.index', array_merge(
            $this->catalogos(),
            compact(
                'movimientos',
                'clienteSeleccionado'
            )
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR PDF
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        $movimientos = $this->filtrar($request)
            ->get();

        $clienteSeleccionado = null;

        if ($request->cliente_id) {

            $clienteSeleccionado = Cliente::find($request->cliente_id);
        }

        $pdf = Pdf::loadView(
            'reportes.pdf',
            compact(
                'movimientos',
                'request',
                'clienteSeleccionado'
            )
        )
        ->setPaper('letter', 'landscape');

        return $pdf->download('reporte-movimientos.pdf');
    }


    /*
    |--------------------------------------------------------------------------
    | IMPRIMIR REPORTE
    |--------------------------------------------------------------------------
    */

    public function imprimir(Request $request)
    {
        $movimientos = $this->filtrar($request)
            ->get();

        $clienteSeleccionado = null;

        if ($request->cliente_id) {

            $clienteSeleccionado = Cliente::find($request->cliente_id);
        }

        return view(
            'reportes.imprimir',
            compact(
                'movimientos',
                'request',
                'clienteSeleccionado'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORTAR EXCEL / CSV
    |--------------------------------------------------------------------------
    */

    public function excel(Request $request)
    {
        $movimientos = $this->filtrar($request)
            ->get();

        $clienteSeleccionado = null;

        if ($request->cliente_id) {

            $clienteSeleccionado = Cliente::find($request->cliente_id);
        }

        $filename = 'reporte-movimientos.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use (
            $movimientos,
            $clienteSeleccionado
        ) {

            $file = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | RESUMEN DEL CLIENTE
            |--------------------------------------------------------------------------
            */

            if ($clienteSeleccionado) {

                fputcsv($file, [
                    'Cliente',
                    $clienteSeleccionado->nombre
                ]);

                fputcsv($file, [
                    'Saldo inicial',
                    number_format(
                        $clienteSeleccionado->saldo_inicial,
                        2,
                        '.',
                        ''
                    )
                ]);

                fputcsv($file, [
                    'Total pagos',
                    number_format(
                        $clienteSeleccionado->totalPagos(),
                        2,
                        '.',
                        ''
                    )
                ]);

                fputcsv($file, [
                    'Saldo actual',
                    number_format(
                        $clienteSeleccionado->saldoActual(),
                        2,
                        '.',
                        ''
                    )
                ]);

                fputcsv($file, [
                    'Saldo a cargo',
                    number_format(
                        $clienteSeleccionado->saldoACargo(),
                        2,
                        '.',
                        ''
                    )
                ]);

                fputcsv($file, [
                    'Saldo a favor',
                    number_format(
                        $clienteSeleccionado->saldoAFavorCalculado(),
                        2,
                        '.',
                        ''
                    )
                ]);

                fputcsv($file, []);
            }


            /*
            |--------------------------------------------------------------------------
            | ENCABEZADOS
            |--------------------------------------------------------------------------
            */

            fputcsv($file, [
                'Folio',
                'Fecha',
                'Tipo',
                'Inmobiliaria',
                'Inmueble',
                'Unidad / Local',
                'Cliente',
                'Concepto',
                'Forma de Pago',
                'Importe',
                'Estado'
            ]);


            /*
            |--------------------------------------------------------------------------
            | DATOS
            |--------------------------------------------------------------------------
            */

            foreach ($movimientos as $m) {

                fputcsv($file, [

                    $m->folio,

                    $m->fecha,

                    $m->naturaleza == 1
                        ? 'Ingreso'
                        : 'Gasto',

                    $m->inmobiliaria->nombre ?? '',

                    $m->inmueble->nombre ?? '',

                    $m->unidad->nombre ?? '',

                    $m->cliente->nombre ?? 'Sin cliente',

                    $m->concepto->nombre ?? '',

                    $m->formaPago->nombre ?? '',

                    $m->importe,

                    $m->estado == 1
                        ? 'Vigente'
                        : 'Cancelado'

                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTRAR MOVIMIENTOS
    |--------------------------------------------------------------------------
    */

    private function filtrar(Request $request)
    {
        return Movimiento::with([

            'cliente',

            'inmobiliaria',

            'inmueble',

            'unidad',

            'concepto',

            'formaPago',

            'cuentaBancaria.banco'

        ])

        /*
        |--------------------------------------------------------------------------
        | FECHAS
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->fecha_inicio,
            fn($q) =>
                $q->whereDate(
                    'fecha',
                    '>=',
                    $request->fecha_inicio
                )
        )

        ->when(
            $request->fecha_fin,
            fn($q) =>
                $q->whereDate(
                    'fecha',
                    '<=',
                    $request->fecha_fin
                )
        )


        /*
        |--------------------------------------------------------------------------
        | INMOBILIARIA
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->inmobiliaria_id,
            fn($q) =>
                $q->where(
                    'inmobiliaria_id',
                    $request->inmobiliaria_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | INMUEBLE
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->inmueble_id,
            fn($q) =>
                $q->where(
                    'inmueble_id',
                    $request->inmueble_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | UNIDAD / LOCAL
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->unidad_id,
            fn($q) =>
                $q->where(
                    'unidad_id',
                    $request->unidad_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | CLIENTE
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->cliente_id,
            fn($q) =>
                $q->where(
                    'cliente_id',
                    $request->cliente_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | CONCEPTO
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->concepto_id,
            fn($q) =>
                $q->where(
                    'concepto_id',
                    $request->concepto_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | FORMA DE PAGO
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->forma_pago_id,
            fn($q) =>
                $q->where(
                    'forma_pago_id',
                    $request->forma_pago_id
                )
        )


        /*
        |--------------------------------------------------------------------------
        | NATURALEZA
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->naturaleza,
            fn($q) =>
                $q->where(
                    'naturaleza',
                    $request->naturaleza
                )
        )


        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        ->when(
            $request->estado,
            fn($q) =>
                $q->where(
                    'estado',
                    $request->estado
                )
        )


        /*
        |--------------------------------------------------------------------------
        | ORDEN
        |--------------------------------------------------------------------------
        */

        ->orderBy('fecha', 'desc');
    }


    /*
    |--------------------------------------------------------------------------
    | CATÁLOGOS PARA FILTROS
    |--------------------------------------------------------------------------
    */

    private function catalogos()
    {
        return [

            'inmobiliarias' => Inmobiliaria::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'inmuebles' => Inmueble::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'unidades' => Unidad::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'clientes' => Cliente::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'conceptos' => Concepto::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

            'formasPago' => FormaPago::where('estado', 1)
                ->orderBy('nombre')
                ->get(),

        ];
    }
}