<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
        'inmueble_id',
        'cliente_id',
        'nombre',
        'razon_social',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    /**
     * Una unidad pertenece a un inmueble.
     */
    public function inmueble(): BelongsTo
    {
        return $this->belongsTo(Inmueble::class);
    }

    /**
     * Una unidad/local puede pertenecer a un cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}