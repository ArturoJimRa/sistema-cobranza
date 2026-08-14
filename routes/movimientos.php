<?php

use App\Http\Controllers\Movimientos\MovimientoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get(
        'movimientos',
        [MovimientoController::class, 'index']
    )->name('movimientos.index');

    Route::get(
        'movimientos/ingresos/create',
        [MovimientoController::class, 'createIngreso']
    )->name('movimientos.ingresos.create');

    Route::post(
        'movimientos/ingresos',
        [MovimientoController::class, 'storeIngreso']
    )->name('movimientos.ingresos.store');

    Route::get(
    'movimientos/gastos/create',
    [MovimientoController::class, 'createGasto']
    )->name('movimientos.gastos.create');

    Route::post(
    'movimientos/gastos',
    [MovimientoController::class, 'storeGasto']
    )->name('movimientos.gastos.store');

    /*
    |--------------------------------------------------------------------------
    | Gastos
    |--------------------------------------------------------------------------
    | Los métodos se agregarán cuando definamos su formulario.
    */

    Route::get(
        'movimientos/{movimiento}/edit',
        [MovimientoController::class, 'edit']
    )->name('movimientos.edit');

    Route::put(
        'movimientos/{movimiento}',
        [MovimientoController::class, 'update']
    )->name('movimientos.update');

    Route::delete(
        'movimientos/{movimiento}',
        [MovimientoController::class, 'destroy']
    )->name('movimientos.destroy');
});