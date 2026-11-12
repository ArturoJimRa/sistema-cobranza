<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reportes\ReporteController;

Route::middleware(['auth'])->group(function () {
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/pdf', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/excel', [ReporteController::class, 'excel'])->name('reportes.excel');
    Route::get('reportes/imprimir', [ReporteController::class, 'imprimir'])->name('reportes.imprimir');
});

/*
|--------------------------------------------------------------------------
| Exportación a Microsip (VENTAF / VENTANF) - solo administradores
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('reportes/microsip', [\App\Http\Controllers\Reportes\MicrosipController::class, 'index'])->name('microsip.index');
    Route::get('reportes/microsip/descargar', [\App\Http\Controllers\Reportes\MicrosipController::class, 'descargar'])->name('microsip.descargar');
});
