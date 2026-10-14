<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {

            /*
             * Se guarda una "fotografía" del cliente dueño del
             * local al momento del movimiento. Esto evita que,
             * si el local cambia de cliente más adelante, el
             * historial de pagos de un cliente se altere.
             */
            $table->foreignId('cliente_id')
                ->nullable()
                ->after('unidad_id')
                ->constrained('clientes')
                ->nullOnDelete();

            $table->index('cliente_id');

        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {

            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');

        });
    }
};
