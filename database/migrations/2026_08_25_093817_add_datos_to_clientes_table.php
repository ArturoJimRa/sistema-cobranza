<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {

            $table->string('nombre')->after('id');

            $table->string('razon_social')->nullable()->after('nombre');

            $table->string('rfc', 20)->nullable()->after('razon_social');

            $table->string('telefono', 30)->nullable()->after('rfc');

            $table->string('correo')->nullable()->after('telefono');

            $table->string('direccion', 500)->nullable()->after('correo');

            $table->boolean('estado')->default(true)->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {

            $table->dropColumn([
                'nombre',
                'razon_social',
                'rfc',
                'telefono',
                'correo',
                'direccion',
                'estado'
            ]);
        });
    }
};