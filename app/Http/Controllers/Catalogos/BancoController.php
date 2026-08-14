<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\BancoRequest;
use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $bancos = Banco::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.bancos.index', compact('bancos', 'buscar'));
    }

    public function create()
    {
        return view('catalogos.bancos.create');
    }

    public function store(BancoRequest $request)
    {
        Banco::create([
            'nombre' => strtoupper($request->nombre),
            'estado' => 1,
        ]);

        return redirect()
            ->route('bancos.index')
            ->with('success', 'Banco registrado correctamente.');
    }

    public function edit(Banco $banco)
    {
        return view('catalogos.bancos.edit', compact('banco'));
    }

    public function update(BancoRequest $request, Banco $banco)
    {
        $banco->update([
            'nombre' => strtoupper($request->nombre),
        ]);

        return redirect()
            ->route('bancos.index')
            ->with('success', 'Banco actualizado correctamente.');
    }

    public function destroy(Banco $banco)
    {
        $banco->estado = !$banco->estado;
        $banco->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}