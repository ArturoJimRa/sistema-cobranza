<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Movimiento;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'razon_social',
        'rfc',
        'telefono',
        'correo',
        'direccion',
        'saldo_inicial',
        'saldo_favor',
        'saldo_vencido',
        'estado',
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'saldo_favor' => 'decimal:2',
        'saldo_vencido' => 'decimal:2',
        'estado' => 'boolean',
    ];

    /**
     * Un cliente puede tener varias unidades/locales.
     */
    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class, 'cliente_id');
    }

    /**
 * Movimientos de ingreso/pago del cliente.
 */
public function movimientos()
{
    return $this->hasMany(Movimiento::class, 'cliente_id');
}

/**
 * Total de pagos/ingresos vigentes realizados por el cliente.
 */
public function totalPagos()
{
    return $this->movimientos()
        ->where('naturaleza', 1)
        ->where('estado', 1)
        ->sum('importe');
}

/**
 * Saldo actual del cliente.
 *
 * Positivo = el cliente todavía debe.
 * Negativo = el cliente tiene saldo a favor.
 */
public function saldoActual()
{
    return (float) $this->saldo_inicial - (float) $this->totalPagos();
}

/**
 * Saldo a cargo del cliente.
 */
public function saldoACargo()
{
    return max(0, $this->saldoActual());
}

/**
 * Saldo a favor del cliente.
 */
public function saldoAFavorCalculado()
{
    return max(0, -$this->saldoActual());
}
}