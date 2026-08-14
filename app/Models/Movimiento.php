<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'folio','fecha','naturaleza','inmobiliaria_id','tipo_inmueble_id',
        'inmueble_id','unidad_id','concepto_id','forma_pago_id',
        'cuenta_bancaria_id','importe','referencia','observaciones',
        'usuario_id','estado','cancelado_en','cancelado_por'
    ];

    public function inmobiliaria(){ return $this->belongsTo(Inmobiliaria::class); }
    public function tipoInmueble(){ return $this->belongsTo(TipoInmueble::class, 'tipo_inmueble_id'); }
    public function inmueble(){ return $this->belongsTo(Inmueble::class); }
    public function unidad(){ return $this->belongsTo(Unidad::class); }
    public function concepto(){ return $this->belongsTo(Concepto::class); }
    public function formaPago(){ return $this->belongsTo(FormaPago::class, 'forma_pago_id'); }
    public function cuentaBancaria(){ return $this->belongsTo(CuentaBancaria::class, 'cuenta_bancaria_id'); }
    public function usuario(){ return $this->belongsTo(User::class, 'usuario_id'); }
}