<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    use HasFactory;

    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'banco_id',
        'nombre',
        'numero_cuenta',
        'clabe',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}