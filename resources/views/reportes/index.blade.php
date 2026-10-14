@extends('layouts.app')

@section('title', 'Reportes')

@section('content')

{{-- ========================================================= --}}
{{-- FILTROS --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Filtros de Reporte
        </h3>

    </div>


    <form method="GET" action="{{ route('reportes.index') }}">

        <div class="card-body">

            <div class="row">

                {{-- ========================================================= --}}
                {{-- FECHA INICIO --}}
                {{-- ========================================================= --}}

                <div class="col-md-3 mb-3">

                    <label>Fecha inicio</label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        class="form-control"
                        value="{{ request('fecha_inicio') }}"
                    >

                </div>


                {{-- ========================================================= --}}
                {{-- FECHA FIN --}}
                {{-- ========================================================= --}}

                <div class="col-md-3 mb-3">

                    <label>Fecha fin</label>

                    <input
                        type="date"
                        name="fecha_fin"
                        class="form-control"
                        value="{{ request('fecha_fin') }}"
                    >

                </div>


                {{-- ========================================================= --}}
                {{-- TIPO --}}
                {{-- ========================================================= --}}

                <div class="col-md-3 mb-3">

                    <label>Tipo</label>

                    <select
                        name="naturaleza"
                        class="form-control"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="1"
                            {{ request('naturaleza') == 1 ? 'selected' : '' }}
                        >
                            Ingreso
                        </option>

                        <option
                            value="2"
                            {{ request('naturaleza') == 2 ? 'selected' : '' }}
                        >
                            Gasto
                        </option>

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- ESTADO --}}
                {{-- ========================================================= --}}

                <div class="col-md-3 mb-3">

                    <label>Estado</label>

                    <select
                        name="estado"
                        class="form-control"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="1"
                            {{ request('estado') == 1 ? 'selected' : '' }}
                        >
                            Vigente
                        </option>

                        <option
                            value="2"
                            {{ request('estado') == 2 ? 'selected' : '' }}
                        >
                            Cancelado
                        </option>

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- INMOBILIARIA --}}
                {{-- ========================================================= --}}

                <div class="col-md-4 mb-3">

                    <label>Inmobiliaria</label>

                    <select
                        name="inmobiliaria_id"
                        class="form-control"
                    >

                        <option value="">
                            Todas
                        </option>

                        @foreach($inmobiliarias as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ request('inmobiliaria_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- INMUEBLE --}}
                {{-- ========================================================= --}}

                <div class="col-md-4 mb-3">

                    <label>Inmueble</label>

                    <select
                        name="inmueble_id"
                        class="form-control"
                    >

                        <option value="">
                            Todos
                        </option>

                        @foreach($inmuebles as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ request('inmueble_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- UNIDAD / LOCAL --}}
                {{-- ========================================================= --}}

                <div class="col-md-4 mb-3">

                    <label>Unidad / Local</label>

                    <select
                        name="unidad_id"
                        class="form-control"
                    >

                        <option value="">
                            Todos
                        </option>

                        @foreach($unidades as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ request('unidad_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- CLIENTE --}}
                {{-- ========================================================= --}}

                <div class="col-md-6 mb-3">

                    <label>Cliente</label>

                    <select
                        name="cliente_id"
                        class="form-control"
                    >

                        <option value="">
                            Todos los clientes
                        </option>

                        @foreach($clientes as $cliente)

                            <option
                                value="{{ $cliente->id }}"
                                {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}
                            >

                                {{ $cliente->nombre }}

                                @if($cliente->razon_social)
                                    - {{ $cliente->razon_social }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- CONCEPTO --}}
                {{-- ========================================================= --}}

                <div class="col-md-6 mb-3">

                    <label>Concepto</label>

                    <select
                        name="concepto_id"
                        class="form-control"
                    >

                        <option value="">
                            Todos
                        </option>

                        @foreach($conceptos as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ request('concepto_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ========================================================= --}}
                {{-- FORMA DE PAGO --}}
                {{-- ========================================================= --}}

                <div class="col-md-6 mb-3">

                    <label>Forma de pago</label>

                    <select
                        name="forma_pago_id"
                        class="form-control"
                    >

                        <option value="">
                            Todas
                        </option>

                        @foreach($formasPago as $item)

                            <option
                                value="{{ $item->id }}"
                                {{ request('forma_pago_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOTONES --}}
        {{-- ========================================================= --}}

        <div class="card-footer">

            {{-- Buscar --}}

            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="fas fa-search"></i>

                Buscar

            </button>


            {{-- Limpiar --}}

            <a
                href="{{ route('reportes.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-eraser"></i>

                Limpiar

            </a>


            {{-- PDF --}}

            <a
                href="{{ route('reportes.pdf', request()->query()) }}"
                class="btn btn-danger"
            >

                <i class="fas fa-file-pdf"></i>

                PDF

            </a>


            {{-- Excel --}}

            <a
                href="{{ route('reportes.excel', request()->query()) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel"></i>

                Excel

            </a>


            {{-- Imprimir --}}

            <a
                href="{{ route('reportes.imprimir', request()->query()) }}"
                target="_blank"
                class="btn btn-dark"
            >

                <i class="fas fa-print"></i>

                Imprimir

            </a>

        </div>

    </form>

</div>



{{-- ========================================================= --}}
{{-- RESUMEN FINANCIERO DEL CLIENTE --}}
{{-- ========================================================= --}}

@if($clienteSeleccionado)

    @php

        $saldoInicial = (float) $clienteSeleccionado->saldo_inicial;

        $totalPagos = (float) $clienteSeleccionado->totalPagos();

        $saldoActual = (float) $clienteSeleccionado->saldoActual();

        $saldoACargo = (float) $clienteSeleccionado->saldoACargo();

        $saldoAFavor = (float) $clienteSeleccionado->saldoAFavorCalculado();

    @endphp


    <div class="card">

        <div class="card-header">

            <h3 class="card-title">

                <i class="fas fa-user"></i>

                Resumen financiero del cliente

            </h3>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- ========================================================= --}}
                {{-- CLIENTE --}}
                {{-- ========================================================= --}}

                <div class="col-md-12 mb-3">

                    <h4 class="mb-0">

                        {{ $clienteSeleccionado->nombre }}

                    </h4>

                    @if($clienteSeleccionado->razon_social)

                        <small class="text-muted">

                            {{ $clienteSeleccionado->razon_social }}

                        </small>

                    @endif

                </div>


                {{-- ========================================================= --}}
                {{-- SALDO INICIAL --}}
                {{-- ========================================================= --}}

                <div class="col-md-3">

                    <div class="small text-muted">
                        Saldo inicial
                    </div>

                    <h4>

                        ${{ number_format($saldoInicial, 2) }}

                    </h4>

                </div>


                {{-- ========================================================= --}}
                {{-- TOTAL PAGOS --}}
                {{-- ========================================================= --}}

                <div class="col-md-3">

                    <div class="small text-muted">
                        Total pagos
                    </div>

                    <h4 class="text-success">

                        ${{ number_format($totalPagos, 2) }}

                    </h4>

                </div>


                {{-- ========================================================= --}}
                {{-- SALDO A CARGO --}}
                {{-- ========================================================= --}}

                <div class="col-md-3">

                    <div class="small text-muted">
                        Saldo a cargo
                    </div>

                    <h4 class="text-danger">

                        ${{ number_format($saldoACargo, 2) }}

                    </h4>

                </div>


                {{-- ========================================================= --}}
                {{-- SALDO A FAVOR --}}
                {{-- ========================================================= --}}

                <div class="col-md-3">

                    <div class="small text-muted">
                        Saldo a favor
                    </div>

                    <h4 class="text-info">

                        ${{ number_format($saldoAFavor, 2) }}

                    </h4>

                </div>

            </div>


            <hr>


            {{-- ========================================================= --}}
            {{-- SALDO ACTUAL --}}
            {{-- ========================================================= --}}

            <div class="row">

                <div class="col-md-12">

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>
                            Saldo actual
                        </strong>


                        @if($saldoActual > 0)

                            <span class="badge badge-danger p-2">

                                Saldo a cargo:
                                ${{ number_format($saldoActual, 2) }}

                            </span>

                        @elseif($saldoActual < 0)

                            <span class="badge badge-info p-2">

                                Saldo a favor:
                                ${{ number_format(abs($saldoActual), 2) }}

                            </span>

                        @else

                            <span class="badge badge-success p-2">

                                Cuenta al corriente

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif



{{-- ========================================================= --}}
{{-- RESULTADOS --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Resultados

        </h3>

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

                    <th>Cliente</th>

                    <th>Unidad / Local</th>

                    <th>Concepto</th>

                    <th>Forma de pago</th>

                    <th>Cuenta bancaria</th>

                    <th>Importe</th>

                    <th>Estado</th>

                </tr>

            </thead>


            <tbody>

                @forelse($movimientos as $m)

                    <tr class="{{ $m->estado == 2 ? 'table-danger' : '' }}">


                        {{-- ========================================================= --}}
                        {{-- FOLIO --}}
                        {{-- ========================================================= --}}

                        <td>

                            {{ $m->folio }}

                        </td>


                        {{-- ========================================================= --}}
                        {{-- FECHA --}}
                        {{-- ========================================================= --}}

                        <td>

                            {{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}

                        </td>


                        {{-- ========================================================= --}}
                        {{-- TIPO --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->naturaleza == 1)

                                <span class="badge badge-success">

                                    Ingreso

                                </span>

                            @else

                                <span class="badge badge-warning">

                                    Gasto

                                </span>

                            @endif

                        </td>


                        {{-- ========================================================= --}}
                        {{-- INMOBILIARIA --}}
                        {{-- ========================================================= --}}

                        <td>

                            {{ $m->inmobiliaria->nombre ?? '—' }}

                        </td>


                        {{-- ========================================================= --}}
                        {{-- INMUEBLE --}}
                        {{-- ========================================================= --}}

                        <td>

                            {{ $m->inmueble->nombre ?? '—' }}

                        </td>


                        {{-- ========================================================= --}}
                        {{-- CLIENTE --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->cliente)

                                <strong>

                                    {{ $m->cliente->nombre }}

                                </strong>


                                @if($m->cliente->razon_social)

                                    <small class="text-muted d-block">

                                        {{ $m->cliente->razon_social }}

                                    </small>

                                @endif

                            @else

                                <span class="text-muted">

                                    Sin cliente asignado

                                </span>

                            @endif

                        </td>


                        {{-- ========================================================= --}}
                        {{-- UNIDAD / LOCAL --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->unidad)

                                <span class="badge badge-info">

                                    {{ $m->unidad->nombre }}

                                </span>

                            @else

                                —

                            @endif

                        </td>


                        {{-- ========================================================= --}}
                        {{-- CONCEPTO --}}
                        {{-- ========================================================= --}}

                        <td>

                            {{ $m->concepto->nombre ?? '—' }}

                        </td>


                        {{-- ========================================================= --}}
                        {{-- FORMA DE PAGO --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->formaPago)

                                {{ $m->formaPago->nombre }}

                            @else

                                —

                            @endif

                        </td>


                        {{-- ========================================================= --}}
                        {{-- CUENTA BANCARIA --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->cuentaBancaria)

                                <strong>

                                    {{ $m->cuentaBancaria->nombre }}

                                </strong>


                                @if($m->cuentaBancaria->banco)

                                    <small class="text-muted d-block">

                                        {{ $m->cuentaBancaria->banco->nombre }}

                                    </small>

                                @endif

                            @else

                                —

                            @endif

                        </td>


                        {{-- ========================================================= --}}
                        {{-- IMPORTE --}}
                        {{-- ========================================================= --}}

                        <td>

                            <strong>

                                ${{ number_format($m->importe, 2) }}

                            </strong>

                        </td>


                        {{-- ========================================================= --}}
                        {{-- ESTADO --}}
                        {{-- ========================================================= --}}

                        <td>

                            @if($m->estado == 1)

                                <span class="badge badge-success">

                                    Vigente

                                </span>

                            @else

                                <span class="badge badge-danger">

                                    Cancelado

                                </span>

                            @endif

                        </td>


                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="12"
                            class="text-center text-muted"
                        >

                            <i class="fas fa-info-circle"></i>

                            No existen resultados con los filtros seleccionados.

                        </td>

                    </tr>


                @endforelse


            </tbody>

        </table>

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINACIÓN --}}
    {{-- ========================================================= --}}

    <div class="card-footer">

        {{ $movimientos->appends(request()->query())->links() }}

    </div>

</div>

@endsection