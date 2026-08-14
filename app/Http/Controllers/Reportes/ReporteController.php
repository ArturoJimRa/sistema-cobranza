<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        $movimientos = $this->filtrar($request)->paginate(20);

        return view('reportes.index', array_merge(
            $this->catalogos(),
            compact('movimientos')
        ));
    }

    public function pdf(Request $request)
    {
        $movimientos = $this->filtrar($request)->get();

        $pdf = Pdf::loadView('reportes.pdf', compact('movimientos', 'request'))
            ->setPaper('letter', 'landscape');

        return $pdf->download('reporte-movimientos.pdf');
    }

    public function imprimir(Request $request)
    {
        $movimientos = $this->filtrar($request)->get();

        return view('reportes.imprimir', compact('movimientos', 'request'));
    }

    public function excel(Request $request)
    {
        $movimientos = $this->filtrar($request)->get();

        $filename = 'reporte-movimientos.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($movimientos) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Folio',
                'Fecha',
                'Tipo',
                'Inmobiliaria',
                'Inmueble',
                'Unidad',
                'Concepto',
                'Forma Pago',
                'Importe',
                'Estado'
            ]);

            foreach ($movimientos as $m) {
                fputcsv($file, [
                    $m->folio,
                    $m->fecha,
                    $m->naturaleza == 1 ? 'Ingreso' : 'Gasto',
                    $m->inmobiliaria->nombre ?? '',
                    $m->inmueble->nombre ?? '',
                    $m->unidad->nombre ?? '',
                    $m->concepto->nombre ?? '',
                    $m->formaPago->nombre ?? '',
                    $m->importe,
                    $m->estado == 1 ? 'Vigente' : 'Cancelado'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function filtrar(Request $request)
    {
        return Movimiento::with([
            'inmobiliaria',
            'inmueble',
            'unidad',
            'concepto',
            'formaPago'
        ])
        ->when($request->fecha_inicio, fn($q) =>
            $q->whereDate('fecha', '>=', $request->fecha_inicio)
        )
        ->when($request->fecha_fin, fn($q) =>
            $q->whereDate('fecha', '<=', $request->fecha_fin)
        )
        ->when($request->inmobiliaria_id, fn($q) =>
            $q->where('inmobiliaria_id', $request->inmobiliaria_id)
        )
        ->when($request->inmueble_id, fn($q) =>
            $q->where('inmueble_id', $request->inmueble_id)
        )
        ->when($request->unidad_id, fn($q) =>
            $q->where('unidad_id', $request->unidad_id)
        )
        ->when($request->concepto_id, fn($q) =>
            $q->where('concepto_id', $request->concepto_id)
        )
        ->when($request->forma_pago_id, fn($q) =>
            $q->where('forma_pago_id', $request->forma_pago_id)
        )
        ->when($request->naturaleza, fn($q) =>
            $q->where('naturaleza', $request->naturaleza)
        )
        ->when($request->estado, fn($q) =>
            $q->where('estado', $request->estado)
        )
        ->orderBy('fecha', 'desc');
    }

    private function catalogos()
    {
        return [
            'inmobiliarias' => Inmobiliaria::where('estado', 1)->orderBy('nombre')->get(),
            'inmuebles' => Inmueble::where('estado', 1)->orderBy('nombre')->get(),
            'unidades' => Unidad::where('estado', 1)->orderBy('nombre')->get(),
            'conceptos' => Concepto::where('estado', 1)->orderBy('nombre')->get(),
            'formasPago' => FormaPago::where('estado', 1)->orderBy('nombre')->get(),
        ];
    }
}