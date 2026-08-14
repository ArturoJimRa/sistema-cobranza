<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\ConceptoRequest;
use App\Models\Concepto;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $tipo = $request->tipo;

        $conceptos = Concepto::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%");
            })
            ->when($tipo, function ($query) use ($tipo) {
                $query->where('tipo', $tipo);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.conceptos.index', compact('conceptos', 'buscar', 'tipo'));
    }

    public function create()
    {
        return view('catalogos.conceptos.create');
    }

    public function store(ConceptoRequest $request)
    {
        Concepto::create([
            'tipo' => $request->tipo,
            'nombre' => strtoupper($request->nombre),
            'estado' => 1,
        ]);

        return redirect()
            ->route('conceptos.index')
            ->with('success', 'Concepto registrado correctamente.');
    }

    public function edit(Concepto $concepto)
    {
        return view('catalogos.conceptos.edit', compact('concepto'));
    }

    public function update(ConceptoRequest $request, Concepto $concepto)
    {
        $concepto->update([
            'tipo' => $request->tipo,
            'nombre' => strtoupper($request->nombre),
        ]);

        return redirect()
            ->route('conceptos.index')
            ->with('success', 'Concepto actualizado correctamente.');
    }

    public function destroy(Concepto $concepto)
    {
        $concepto->estado = !$concepto->estado;
        $concepto->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}