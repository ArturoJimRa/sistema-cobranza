<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clientes\ClienteController;

Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('clientes', ClienteController::class);

});