@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<div class="row">

    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>${{ number_format($cobradoHoy, 2) }}</h3>
                <p>Cobrado Hoy</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>${{ number_format($cobradoMes, 2) }}</h3>
                <p>Cobrado Mes</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>${{ number_format($gastosMes, 2) }}</h3>
                <p>Gastos Mes</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalMovimientos }}</h3>
                <p>Movimientos</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>

</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Últimos movimientos</h3>
    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-striped">

            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Inmobiliaria</th>
                    <th>Inmueble</th>
                    <th>Unidad</th>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>

                @forelse($ultimosMovimientos as $movimiento)

                    <tr class="{{ $movimiento->estado == 2 ? 'table-danger' : '' }}">

                        <td>{{ $movimiento->folio }}</td>

                        <td>{{ $movimiento->fecha }}</td>

                        <td>
                            @if($movimiento->naturaleza == 1)
                                <span class="badge badge-success">Ingreso</span>
                            @else
                                <span class="badge badge-danger">Gasto</span>
                            @endif
                        </td>

                        <td>{{ $movimiento->inmobiliaria->nombre ?? '' }}</td>

                        <td>{{ $movimiento->inmueble->nombre ?? '' }}</td>

                        <td>{{ $movimiento->unidad->nombre ?? '' }}</td>

                        <td>{{ $movimiento->concepto->nombre ?? '' }}</td>

                        <td>${{ number_format($movimiento->importe, 2) }}</td>

                        <td>
                            @if($movimiento->estado == 1)
                                <span class="badge badge-success">Vigente</span>
                            @else
                                <span class="badge badge-danger">Cancelado</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center">
                            No hay movimientos registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection