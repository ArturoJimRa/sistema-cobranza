<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inmuebles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('inmobiliaria_id')->constrained('inmobiliarias');
            $table->foreignId('tipo_inmueble_id')->constrained('tipos_inmueble');

            $table->string('nombre',150);

            $table->text('direccion')->nullable();

            $table->text('observaciones')->nullable();

            $table->tinyInteger('estado')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inmuebles');
    }
};