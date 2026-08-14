<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_bancarias', function (Blueprint $table) {

            $table->id();

            $table->foreignId('banco_id')->constrained('bancos');

            $table->string('nombre',150);

            $table->string('numero_cuenta',50)->nullable();

            $table->string('clabe',18)->nullable();

            $table->tinyInteger('estado')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};