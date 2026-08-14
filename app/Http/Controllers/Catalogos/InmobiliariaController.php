<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\InmobiliariaRequest;
use App\Models\Inmobiliaria;
use Illuminate\Http\Request;

class InmobiliariaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $inmobiliarias = Inmobiliaria::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.inmobiliarias.index', compact('inmobiliarias', 'buscar'));
    }

    public function create()
    {
        return view('catalogos.inmobiliarias.create');
    }

    public function store(InmobiliariaRequest $request)
{
    Inmobiliaria::create([
        'nombre' => strtoupper($request->nombre),
        'razon_social' => strtoupper($request->razon_social),
        'rfc' => strtoupper($request->rfc),
        'telefono' => $request->telefono,
        'correo' => $request->correo,
        'direccion' => strtoupper($request->direccion),
        'estado' => 1
    ]);

    return redirect()
        ->route('inmobiliarias.index')
        ->with('success', 'Inmobiliaria registrada correctamente.');
}
    public function edit(Inmobiliaria $inmobiliaria)
    {
        return view('catalogos.inmobiliarias.edit', compact('inmobiliaria'));
    }

    public function update(InmobiliariaRequest $request, Inmobiliaria $inmobiliaria)
{
    $inmobiliaria->update([
        'nombre' => strtoupper($request->nombre),
        'razon_social' => strtoupper($request->razon_social),
        'rfc' => strtoupper($request->rfc),
        'telefono' => $request->telefono,
        'correo' => $request->correo,
        'direccion' => strtoupper($request->direccion)
    ]);

    return redirect()
        ->route('inmobiliarias.index')
        ->with('success', 'Inmobiliaria actualizada correctamente.');
}

    public function destroy(Inmobiliaria $inmobiliaria)
{
    $inmobiliaria->estado = $inmobiliaria->estado == 1 ? 0 : 1;

    $inmobiliaria->save();

    return redirect()
        ->route('inmobiliarias.index')
        ->with('success', 'Estado actualizado correctamente.');
}
}