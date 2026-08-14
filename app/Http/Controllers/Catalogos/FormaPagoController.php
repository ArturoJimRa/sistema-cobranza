<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\FormaPagoRequest;
use App\Models\FormaPago;
use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $formasPago = FormaPago::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.formas-pago.index', compact('formasPago', 'buscar'));
    }

    public function create()
    {
        return view('catalogos.formas-pago.create');
    }

    public function store(FormaPagoRequest $request)
    {
        FormaPago::create([
            'nombre' => strtoupper($request->nombre),
            'estado' => 1,
        ]);

        return redirect()
            ->route('formas-pago.index')
            ->with('success', 'Forma de pago registrada correctamente.');
    }

    public function edit(FormaPago $formas_pago)
    {
        $formaPago = $formas_pago;

        return view('catalogos.formas-pago.edit', compact('formaPago'));
    }

    public function update(FormaPagoRequest $request, FormaPago $formas_pago)
    {
        $formas_pago->update([
            'nombre' => strtoupper($request->nombre),
        ]);

        return redirect()
            ->route('formas-pago.index')
            ->with('success', 'Forma de pago actualizada correctamente.');
    }

    public function destroy(FormaPago $formas_pago)
    {
        $formas_pago->estado = !$formas_pago->estado;
        $formas_pago->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}