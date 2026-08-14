<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Catalogos\InmobiliariaController;
use App\Http\Controllers\Catalogos\TipoInmuebleController;
use App\Http\Controllers\Catalogos\ConceptoController;
use App\Http\Controllers\Catalogos\FormaPagoController;
use App\Http\Controllers\Catalogos\BancoController;
use App\Http\Controllers\Catalogos\CuentaBancariaController;
use App\Http\Controllers\Catalogos\InmuebleController;
use App\Http\Controllers\Catalogos\UnidadController;


Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('inmobiliarias', InmobiliariaController::class);
    Route::resource('tipos-inmueble', TipoInmuebleController::class);
    Route::resource('conceptos', ConceptoController::class);
    Route::resource('formas-pago', FormaPagoController::class);
    Route::resource('bancos', BancoController::class);
    Route::resource('cuentas-bancarias', CuentaBancariaController::class);
    Route::resource('inmuebles', InmuebleController::class);
    Route::resource('unidades', UnidadController::class);
    
});