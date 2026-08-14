<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraMovimiento extends Model
{
    protected $table = 'bitacora_movimientos';

    protected $fillable = [
        'movimiento_id',
        'usuario_id',
        'accion',
        'descripcion'
    ];
}