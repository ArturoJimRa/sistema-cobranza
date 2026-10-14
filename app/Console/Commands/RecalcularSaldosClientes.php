<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Movimiento;
use Illuminate\Console\Command;

class RecalcularSaldosClientes extends Command
{
    /**
     * Este comando es de un solo uso, pensado para correrse
     * justo después de instalar la migración que agrega
     * "cliente_id" a movimientos y "saldo_inicial" a clientes.
     *
     * 1) Rellena cliente_id en los ingresos ya existentes que
     *    estén ligados a un local con cliente asignado.
     * 2) Recalcula saldo_favor / saldo_vencido de cada cliente.
     *
     * Uso: php artisan clientes:recalcular-saldos
     */
    protected $signature = 'clientes:recalcular-saldos';

    protected $description = 'Rellena cliente_id en movimientos existentes y recalcula los saldos de todos los clientes';

    public function handle(): int
    {
        $this->info('Rellenando cliente_id en movimientos existentes...');

        $actualizados = 0;

        Movimiento::query()
            ->whereNull('cliente_id')
            ->whereNotNull('unidad_id')
            ->with('unidad')
            ->chunkById(200, function ($movimientos) use (&$actualizados) {
                foreach ($movimientos as $movimiento) {
                    $clienteId = $movimiento->unidad?->cliente_id;

                    if ($clienteId) {
                        $movimiento->update(['cliente_id' => $clienteId]);
                        $actualizados++;
                    }
                }
            });

        $this->info("Movimientos actualizados: {$actualizados}");

        $this->info('Recalculando saldos de clientes...');

        $total = 0;

        Cliente::query()->chunkById(200, function ($clientes) use (&$total) {
            foreach ($clientes as $cliente) {
                $cliente->recalcularSaldo();
                $total++;
            }
        });

        $this->info("Clientes recalculados: {$total}");
        $this->info('Listo.');

        return self::SUCCESS;
    }
}
