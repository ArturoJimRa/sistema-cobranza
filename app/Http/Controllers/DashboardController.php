<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();
        $inicioMes = now()->startOfMonth()->toDateString();
        $finMes = now()->endOfMonth()->toDateString();

        $cobradoHoy = Movimiento::where('estado', 1)
            ->where('naturaleza', 1)
            ->whereDate('fecha', $hoy)
            ->sum('importe');

        $cobradoMes = Movimiento::where('estado', 1)
            ->where('naturaleza', 1)
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->sum('importe');

        $gastosMes = Movimiento::where('estado', 1)
            ->where('naturaleza', 2)
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->sum('importe');

        $totalMovimientos = Movimiento::count();

        $ultimosMovimientos = Movimiento::with([
            'inmobiliaria',
            'inmueble',
            'unidad',
            'concepto'
        ])
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();

        return view('dashboard.index', compact(
            'cobradoHoy',
            'cobradoMes',
            'gastosMes',
            'totalMovimientos',
            'ultimosMovimientos'
        ));
    }
}