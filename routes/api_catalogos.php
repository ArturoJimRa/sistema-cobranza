<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CatalogoController;

Route::middleware('auth')->group(function () {

    Route::get(
        '/ajax/inmuebles/{id}',
        [CatalogoController::class, 'inmuebles']
    );

    Route::get(
        '/ajax/unidades/{id}',
        [CatalogoController::class, 'unidades']
    );

    Route::get(
        '/ajax/cuentas/{id}',
        [CatalogoController::class, 'cuentas']
    );

});