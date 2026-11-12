<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Campos necesarios para exportar los archivos VENTAF / VENTANF a Microsip.
 *
 * - movimientos.facturado : indica si el ingreso se facturó (VENTAF) o no (VENTANF).
 * - conceptos.clave_microsip y formas_pago.clave_microsip : clave con la que
 *   Microsip identifica el concepto / forma de pago. Si queda vacía, el
 *   exportador usa el id del sistema.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->boolean('facturado')->default(false)->after('importe');
            $table->index(['fecha', 'inmobiliaria_id', 'naturaleza', 'estado', 'facturado'], 'movimientos_microsip_index');
        });

        Schema::table('conceptos', function (Blueprint $table) {
            $table->string('clave_microsip', 20)->nullable()->after('nombre');
        });

        Schema::table('formas_pago', function (Blueprint $table) {
            $table->string('clave_microsip', 20)->nullable()->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropIndex('movimientos_microsip_index');
            $table->dropColumn('facturado');
        });

        Schema::table('conceptos', function (Blueprint $table) {
            $table->dropColumn('clave_microsip');
        });

        Schema::table('formas_pago', function (Blueprint $table) {
            $table->dropColumn('clave_microsip');
        });
    }
};
