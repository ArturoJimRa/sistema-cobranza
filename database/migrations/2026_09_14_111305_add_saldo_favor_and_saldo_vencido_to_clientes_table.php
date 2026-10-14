<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->decimal('saldo_favor', 12, 2)
                ->default(0)
                ->after('saldo_inicial');

            $table->decimal('saldo_vencido', 12, 2)
                ->default(0)
                ->after('saldo_favor');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'saldo_favor',
                'saldo_vencido',
            ]);
        });
    }
};