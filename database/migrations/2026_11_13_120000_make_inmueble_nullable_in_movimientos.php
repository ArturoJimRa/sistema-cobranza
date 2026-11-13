<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Los gastos ahora son a nivel de inmobiliaria (plaza) y, opcionalmente,
 * de un cliente: ya no requieren inmueble ni tipo de inmueble.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_inmueble_id')->nullable()->change();
            $table->unsignedBigInteger('inmueble_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_inmueble_id')->nullable(false)->change();
            $table->unsignedBigInteger('inmueble_id')->nullable(false)->change();
        });
    }
};
