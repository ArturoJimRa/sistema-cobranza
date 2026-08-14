<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {

            $table->id();

            $table->string('folio', 20)->unique();

            $table->date('fecha');

            // 1 = Ingreso
            // 2 = Gasto
            $table->tinyInteger('naturaleza');

            $table->foreignId('inmobiliaria_id')->constrained('inmobiliarias');

            $table->foreignId('tipo_inmueble_id')->constrained('tipos_inmueble');

            $table->foreignId('inmueble_id')->constrained('inmuebles');

            $table->foreignId('unidad_id')->constrained('unidades');

            $table->foreignId('concepto_id')->constrained('conceptos');

            $table->foreignId('forma_pago_id')->constrained('formas_pago');

            $table->foreignId('cuenta_bancaria_id')->nullable()->constrained('cuentas_bancarias');

            $table->decimal('importe', 12, 2);

            $table->string('referencia', 150)->nullable();

            $table->text('observaciones')->nullable();

            $table->foreignId('usuario_id')->constrained('users');

            // 1 = Vigente
            // 2 = Cancelado
            $table->tinyInteger('estado')->default(1);

            $table->timestamp('cancelado_en')->nullable();

            $table->foreignId('cancelado_por')
                ->nullable()
                ->constrained('users');

            $table->timestamps();

            $table->index('fecha');
            $table->index('naturaleza');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};