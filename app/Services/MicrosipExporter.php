<?php

namespace App\Services;

use App\Models\Concepto;
use App\Models\FormaPago;
use App\Models\Inmobiliaria;
use App\Models\Movimiento;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

/**
 * Genera los archivos VENTAF (facturado) y VENTANF (no facturado) para Microsip
 * con el mismo layout que los del punto de venta:
 *
 *   Fila 1 : FECHA | VENTAS MOSTRADOR | INMOBILIARIA | CAJERO
 *   Filas  : CLAVE | CONCEPTO | 1/0 (hubo ingreso) | IMPORTE     (todos los conceptos de ingreso)
 *   Filas  :       | FORMA DE PAGO | IMPORTE |                    (todas las formas de pago + filas extra)
 *
 * Solo se toman ingresos (naturaleza = 1) vigentes (estado = 1).
 */
class MicrosipExporter
{
    public const FACTURADO = 'F';
    public const NO_FACTURADO = 'NF';

    /**
     * Totales del día para una inmobiliaria, separados por concepto y forma de pago.
     */
    public function resumen(Carbon $fecha, Inmobiliaria $inmobiliaria, string $tipo): array
    {
        $movimientos = $this->movimientos($fecha, $inmobiliaria, $tipo);

        $porConcepto = $movimientos->groupBy('concepto_id')->map(fn ($g) => (float) $g->sum('importe'));
        $porFormaPago = $movimientos->groupBy('forma_pago_id')->map(fn ($g) => (float) $g->sum('importe'));

        $conceptos = Concepto::where('tipo', 1)
            ->where(function ($q) use ($porConcepto) {
                // Conceptos activos + cualquier inactivo que tenga movimientos ese día
                $q->where('estado', 1)->orWhereIn('id', $porConcepto->keys());
            })
            ->orderBy('id')
            ->get()
            ->map(fn (Concepto $c) => [
                'clave' => $c->clave_microsip ?: (string) $c->id,
                'sin_clave' => blank($c->clave_microsip),
                'nombre' => $c->nombre,
                'importe' => $porConcepto->get($c->id, 0.0),
            ]);

        $formasPago = FormaPago::where(function ($q) use ($porFormaPago) {
                $q->where('estado', 1)->orWhereIn('id', $porFormaPago->keys());
            })
            ->orderBy('nombre')
            ->get()
            ->map(fn (FormaPago $f) => [
                'nombre' => $f->nombre,
                'importe' => $porFormaPago->get($f->id, 0.0),
            ]);

        foreach (config('microsip.filas_extra', []) as $extra) {
            $formasPago->push(['nombre' => $extra, 'importe' => 0.0]);
        }

        return [
            'fecha' => $fecha,
            'inmobiliaria' => $inmobiliaria,
            'tipo' => $tipo,
            'movimientos' => $movimientos->count(),
            'conceptos' => $conceptos,
            'formas_pago' => $formasPago,
            'total_conceptos' => round($conceptos->sum('importe'), 2),
            'total_formas_pago' => round($formasPago->sum('importe'), 2),
        ];
    }

    /**
     * Escribe el .xls en un archivo temporal y regresa [ruta, nombre de descarga].
     */
    public function generar(Carbon $fecha, Inmobiliaria $inmobiliaria, string $tipo): array
    {
        $datos = $this->resumen($fecha, $inmobiliaria, $tipo);
        $formato = config('microsip.formato_importe');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hoja1');

        // Encabezado
        $sheet->setCellValueExplicit('A1', $fecha->format(config('microsip.formato_fecha')), DataType::TYPE_STRING);
        $sheet->setCellValue('B1', config('microsip.encabezado_tipo'));
        $sheet->setCellValue('C1', Str::upper($inmobiliaria->nombre));
        $sheet->setCellValue('D1', config('microsip.encabezado_cajero'));
        $sheet->getStyle('D1')->getNumberFormat()->setFormatCode($formato);

        $fila = 2;

        // Conceptos de ingreso (equivalente a las familias del POS)
        foreach ($datos['conceptos'] as $c) {
            $clave = $c['clave'];
            ctype_digit($clave)
                ? $sheet->setCellValue("A{$fila}", (int) $clave)
                : $sheet->setCellValueExplicit("A{$fila}", $clave, DataType::TYPE_STRING);

            $sheet->setCellValue("B{$fila}", $c['nombre']);
            $sheet->setCellValue("C{$fila}", $c['importe'] > 0 ? 1 : 0);
            $sheet->setCellValue("D{$fila}", $c['importe']);
            $sheet->getStyle("D{$fila}")->getNumberFormat()->setFormatCode($formato);
            $fila++;
        }

        // Formas de pago (+ filas extra como DESCUENTOS)
        foreach ($datos['formas_pago'] as $f) {
            $sheet->setCellValue("B{$fila}", $f['nombre']);
            $sheet->setCellValue("C{$fila}", $f['importe']);
            $sheet->getStyle("C{$fila}:D{$fila}")->getNumberFormat()->setFormatCode($formato);
            $fila++;
        }

        $nombre = $this->nombreArchivo($fecha, $inmobiliaria, $tipo);
        $ruta = tempnam(sys_get_temp_dir(), 'microsip_');

        (new Xls($spreadsheet))->save($ruta);
        $spreadsheet->disconnectWorksheets();

        return [$ruta, $nombre];
    }

    public function nombreArchivo(Carbon $fecha, Inmobiliaria $inmobiliaria, string $tipo): string
    {
        $prefijo = $tipo === self::FACTURADO
            ? config('microsip.prefijo_facturado')
            : config('microsip.prefijo_no_facturado');

        $unidad = Str::of(Str::ascii($inmobiliaria->nombre))
            ->upper()
            ->replaceMatches('/[^A-Z0-9]+/', '_')
            ->trim('_');

        return "{$prefijo}_{$unidad}_{$fecha->format('Ymd')}.xls";
    }

    private function movimientos(Carbon $fecha, Inmobiliaria $inmobiliaria, string $tipo): Collection
    {
        return Movimiento::query()
            ->whereDate('fecha', $fecha->toDateString())
            ->where('inmobiliaria_id', $inmobiliaria->id)
            ->where('naturaleza', 1)
            ->where('estado', 1)
            ->where('facturado', $tipo === self::FACTURADO)
            ->get(['id', 'concepto_id', 'forma_pago_id', 'importe']);
    }
}
