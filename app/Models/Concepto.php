<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    protected $table = 'conceptos';

    protected $fillable = [
        'tipo',
        'nombre',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];
}