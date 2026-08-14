<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inmobiliaria extends Model
{
    use HasFactory;

    protected $table = 'inmobiliarias';

    protected $fillable = [
        'nombre',
        'razon_social',
        'rfc',
        'telefono',
        'correo',
        'direccion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}