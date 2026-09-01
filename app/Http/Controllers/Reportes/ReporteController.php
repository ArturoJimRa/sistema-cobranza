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

        return view('reportes.index', array_merge(
            $this->catalogos(),
            compact('movimientos')
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

        $pdf = Pdf::loadView(
            'reportes.pdf',
            compact('movimientos', 'request')
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

        return view(
            'reportes.imprimir',
            compact('movimientos', 'request')
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

        $filename = 'reporte-movimientos.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($movimientos) {

            $file = fopen('php://output', 'w');

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

                    /*
                    |--------------------------------------------------------------------------
                    | CLIENTE
                    |--------------------------------------------------------------------------
                    */

                    $m->unidad && $m->unidad->cliente
                        ? $m->unidad->cliente->nombre
                        : 'Sin cliente',

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

            'inmobiliaria',

            'inmueble',

            /*
            |--------------------------------------------------------------------------
            | UNIDAD Y CLIENTE
            |--------------------------------------------------------------------------
            */

            'unidad.cliente',

            'concepto',

            'formaPago'

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
                $q->whereHas('unidad', function ($query) use ($request) {

                    $query->where(
                        'cliente_id',
                        $request->cliente_id
                    );

                })
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

            /*
            |--------------------------------------------------------------------------
            | CLIENTES
            |--------------------------------------------------------------------------
            */

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