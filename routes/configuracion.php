<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configuracion\UsuarioController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});