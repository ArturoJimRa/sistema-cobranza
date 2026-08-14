<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
        'inmueble_id',
        'nombre',
        'razon_social',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class);
    }
}