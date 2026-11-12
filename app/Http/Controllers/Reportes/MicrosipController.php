<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Inmobiliaria;
use App\Services\MicrosipExporter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MicrosipController extends Controller
{
    public function __construct(private MicrosipExporter $exporter)
    {
    }

    /**
     * Pantalla para elegir fecha e inmobiliaria y ver la vista previa
     * de lo que irá en VENTAF y VENTANF.
     */
    public function index(Request $request)
    {
        $inmobiliarias = Inmobiliaria::where('estado', 1)->orderBy('nombre')->get();

        $request->validate([
            'fecha' => ['nullable', 'date'],
            'inmobiliaria_id' => ['nullable', 'exists:inmobiliarias,id'],
        ]);

        $fecha = Carbon::parse($request->input('fecha', now()->toDateString()));

        $inmobiliaria = $request->filled('inmobiliaria_id')
            ? Inmobiliaria::find($request->inmobiliaria_id)
            : ($inmobiliarias->count() === 1 ? $inmobiliarias->first() : null);

        $previas = [];

        if ($inmobiliaria) {
            foreach ([MicrosipExporter::FACTURADO, MicrosipExporter::NO_FACTURADO] as $tipo) {
                $previas[$tipo] = $this->exporter->resumen($fecha, $inmobiliaria, $tipo) + [
                    'archivo' => $this->exporter->nombreArchivo($fecha, $inmobiliaria, $tipo),
                ];
            }
        }

        return view('reportes.microsip', compact('inmobiliarias', 'fecha', 'inmobiliaria', 'previas'));
    }

    /**
     * Descarga el .xls (tipo F = VENTAF, NF = VENTANF).
     */
    public function descargar(Request $request)
    {
        $datos = $request->validate([
            'fecha' => ['required', 'date'],
            'inmobiliaria_id' => ['required', 'exists:inmobiliarias,id'],
            'tipo' => ['required', Rule::in([MicrosipExporter::FACTURADO, MicrosipExporter::NO_FACTURADO])],
        ]);

        [$ruta, $nombre] = $this->exporter->generar(
            Carbon::parse($datos['fecha']),
            Inmobiliaria::findOrFail($datos['inmobiliaria_id']),
            $datos['tipo']
        );

        return response()
            ->download($ruta, $nombre, ['Content-Type' => 'application/vnd.ms-excel'])
            ->deleteFileAfterSend(true);
    }
}
