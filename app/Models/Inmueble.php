<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inmueble extends Model
{
    use HasFactory;

    protected $table = 'inmuebles';

    protected $fillable = [
        'inmobiliaria_id',
        'tipo_inmueble_id',
        'nombre',
        'direccion',
        'observaciones',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function inmobiliaria()
    {
        return $this->belongsTo(Inmobiliaria::class);
    }

    public function tipoInmueble()
    {
        return $this->belongsTo(TipoInmueble::class, 'tipo_inmueble_id');
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class);
    }
}