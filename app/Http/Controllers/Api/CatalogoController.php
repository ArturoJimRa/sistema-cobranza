<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use App\Models\Unidad;
use App\Models\CuentaBancaria;

class CatalogoController extends Controller
{
    public function inmuebles($id)
    {
        return Inmueble::where('inmobiliaria_id', $id)
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();
    }

    public function unidades($id)
    {
        return Unidad::where('inmueble_id', $id)
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();
    }

    public function cuentas($id)
    {
        return CuentaBancaria::where('banco_id', $id)
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();
    }
}