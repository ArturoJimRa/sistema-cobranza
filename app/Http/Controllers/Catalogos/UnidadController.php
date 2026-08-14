<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\UnidadRequest;
use App\Models\Inmueble;
use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $unidades = Unidad::with('inmueble')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('razon_social', 'LIKE', "%{$buscar}%")
                    ->orWhereHas('inmueble', function ($q) use ($buscar) {
                        $q->where('nombre', 'LIKE', "%{$buscar}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.unidades.index', compact('unidades', 'buscar'));
    }

    public function create()
    {
        $inmuebles = Inmueble::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.unidades.create', compact('inmuebles'));
    }

    public function store(UnidadRequest $request)
    {
        Unidad::create([
            'inmueble_id' => $request->inmueble_id,
            'nombre' => strtoupper($request->nombre),
            'razon_social' => strtoupper($request->razon_social),
            'estado' => 1,
        ]);

        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad registrada correctamente.');
    }

    public function edit(Unidad $unidad)
    {
        $inmuebles = Inmueble::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.unidades.edit', compact('unidad', 'inmuebles'));
    }

    public function update(UnidadRequest $request, Unidad $unidad)
    {
        $unidad->update([
            'inmueble_id' => $request->inmueble_id,
            'nombre' => strtoupper($request->nombre),
            'razon_social' => strtoupper($request->razon_social),
        ]);

        return redirect()
            ->route('unidades.index')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad)
    {
        $unidad->estado = !$unidad->estado;
        $unidad->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}