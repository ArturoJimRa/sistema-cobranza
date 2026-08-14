<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogos\CuentaBancariaRequest;
use App\Models\Banco;
use App\Models\CuentaBancaria;
use Illuminate\Http\Request;

class CuentaBancariaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $cuentas = CuentaBancaria::with('banco')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('numero_cuenta', 'LIKE', "%{$buscar}%")
                    ->orWhere('clabe', 'LIKE', "%{$buscar}%")
                    ->orWhereHas('banco', function ($q) use ($buscar) {
                        $q->where('nombre', 'LIKE', "%{$buscar}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogos.cuentas-bancarias.index', compact('cuentas', 'buscar'));
    }

    public function create()
    {
        $bancos = Banco::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.cuentas-bancarias.create', compact('bancos'));
    }

    public function store(CuentaBancariaRequest $request)
    {
        CuentaBancaria::create([
            'banco_id' => $request->banco_id,
            'nombre' => strtoupper($request->nombre),
            'numero_cuenta' => $request->numero_cuenta,
            'clabe' => $request->clabe,
            'estado' => 1,
        ]);

        return redirect()
            ->route('cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria registrada correctamente.');
    }

    public function edit(CuentaBancaria $cuentas_bancaria)
    {
        $cuenta = $cuentas_bancaria;
        $bancos = Banco::where('estado', 1)->orderBy('nombre')->get();

        return view('catalogos.cuentas-bancarias.edit', compact('cuenta', 'bancos'));
    }

    public function update(CuentaBancariaRequest $request, CuentaBancaria $cuentas_bancaria)
    {
        $cuentas_bancaria->update([
            'banco_id' => $request->banco_id,
            'nombre' => strtoupper($request->nombre),
            'numero_cuenta' => $request->numero_cuenta,
            'clabe' => $request->clabe,
        ]);

        return redirect()
            ->route('cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria actualizada correctamente.');
    }

    public function destroy(CuentaBancaria $cuentas_bancaria)
    {
        $cuentas_bancaria->estado = !$cuentas_bancaria->estado;
        $cuentas_bancaria->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}