<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\InmuebleRequest;
use App\Models\Inmobiliaria;
use App\Models\Inmueble;
use App\Models\TipoInmueble;
use Illuminate\Http\Request;

class InmuebleController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $inmuebles = Inmueble::with(['inmobiliaria', 'tipoInmueble'])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhereHas('inmobiliaria', function ($q) use ($buscar) {
                        $q->where('nombre', 'LIKE', "%{$buscar}%");
                    })
                    ->orWhereHas('tipoInmueble', function ($q) use ($buscar) {
                        $q->where('nombre', 'LIKE', "%{$buscar}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.inmuebles.index', compact('inmuebles', 'buscar'));
    }

    public function create()
    {
        $inmobiliarias = Inmobiliaria::where('estado', 1)->orderBy('nombre')->get();
        $tipos = TipoInmueble::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.inmuebles.create', compact('inmobiliarias', 'tipos'));
    }

    public function store(InmuebleRequest $request)
    {
        Inmueble::create([
            'inmobiliaria_id' => $request->inmobiliaria_id,
            'tipo_inmueble_id' => $request->tipo_inmueble_id,
            'nombre' => strtoupper($request->nombre),
            'direccion' => strtoupper($request->direccion),
            'observaciones' => strtoupper($request->observaciones),
            'estado' => 1,
        ]);

        return redirect()
            ->route('inmuebles.index')
            ->with('success', 'Inmueble registrado correctamente.');
    }

    public function edit(Inmueble $inmueble)
    {
        $inmobiliarias = Inmobiliaria::where('estado', 1)->orderBy('nombre')->get();
        $tipos = TipoInmueble::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.inmuebles.edit', compact('inmueble', 'inmobiliarias', 'tipos'));
    }

    public function update(InmuebleRequest $request, Inmueble $inmueble)
    {
        $inmueble->update([
            'inmobiliaria_id' => $request->inmobiliaria_id,
            'tipo_inmueble_id' => $request->tipo_inmueble_id,
            'nombre' => strtoupper($request->nombre),
            'direccion' => strtoupper($request->direccion),
            'observaciones' => strtoupper($request->observaciones),
        ]);

        return redirect()
            ->route('inmuebles.index')
            ->with('success', 'Inmueble actualizado correctamente.');
    }

    public function destroy(Inmueble $inmueble)
    {
        $inmueble->estado = !$inmueble->estado;
        $inmueble->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}