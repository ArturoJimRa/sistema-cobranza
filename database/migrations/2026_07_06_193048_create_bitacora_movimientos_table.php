<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_movimientos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('movimiento_id')->constrained('movimientos');

            $table->foreignId('usuario_id')->constrained('users');

            $table->string('accion',100);

            $table->text('descripcion')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_movimientos');
    }
};