<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {

            /*
             * Saldo con el que entra el cliente al sistema,
             * capturado manualmente por el capturista.
             *
             * Convención:
             *   positivo = el cliente debía (saldo vencido)
             *   negativo = el cliente tenía saldo a favor
             */
            $table->decimal('saldo_inicial', 12, 2)
                ->default(0)
                ->after('direccion');

        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {

            $table->dropColumn('saldo_inicial');

        });
    }
};
