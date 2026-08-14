@extends('layouts.app')

@section('title', 'Reportes')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Filtros de Reporte</h3>
    </div>

    <form method="GET" action="{{ route('reportes.index') }}">

        <div class="card-body">

            <div class="row">

                {{-- Fecha inicio --}}
                <div class="col-md-3 mb-3">
                    <label>Fecha inicio</label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        class="form-control"
                        value="{{ request('fecha_inicio') }}"
                    >
                </div>

                {{-- Fecha fin --}}
                <div class="col-md-3 mb-3">
                    <label>Fecha fin</label>

                    <input
                        type="date"
                        name="fecha_fin"
                        class="form-control"
                        value="{{ request('fecha_fin') }}"
                    >
                </div>

                {{-- Tipo --}}
                <div class="col-md-3 mb-3">
                    <label>Tipo</label>

                    <select name="naturaleza" class="form-control">

                        <option value="">Todos</option>

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

                {{-- Estado --}}
                <div class="col-md-3 mb-3">
                    <label>Estado</label>

                    <select name="estado" class="form-control">

                        <option value="">Todos</option>

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

                {{-- Inmobiliaria --}}
                <div class="col-md-4 mb-3">
                    <label>Inmobiliaria</label>

                    <select name="inmobiliaria_id" class="form-control">

                        <option value="">Todas</option>

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

                {{-- Inmueble --}}
                <div class="col-md-4 mb-3">
                    <label>Inmueble</label>

                    <select name="inmueble_id" class="form-control">

                        <option value="">Todos</option>

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

                {{-- Unidad --}}
                <div class="col-md-4 mb-3">
                    <label>Unidad</label>

                    <select name="unidad_id" class="form-control">

                        <option value="">Todas</option>

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

                {{-- Concepto --}}
                <div class="col-md-6 mb-3">
                    <label>Concepto</label>

                    <select name="concepto_id" class="form-control">

                        <option value="">Todos</option>

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

                {{-- Forma de pago --}}
                <div class="col-md-6 mb-3">
                    <label>Forma de pago</label>

                    <select name="forma_pago_id" class="form-control">

                        <option value="">Todas</option>

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

        <div class="card-footer">

            {{-- Buscar --}}
            <button type="submit" class="btn btn-primary">
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

                    <th>Unidad</th>

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

                    {{-- Folio --}}
                    <td>
                        {{ $m->folio }}
                    </td>

                    {{-- Fecha --}}
                    <td>
                        {{ $m->fecha }}
                    </td>

                    {{-- Tipo --}}
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

                    {{-- Inmobiliaria --}}
                    <td>
                        {{ $m->inmobiliaria->nombre ?? '—' }}
                    </td>

                    {{-- Inmueble --}}
                    <td>
                        {{ $m->inmueble->nombre ?? '—' }}
                    </td>

                    {{-- Unidad --}}
                    <td>
                        {{ $m->unidad->nombre ?? '—' }}
                    </td>

                    {{-- Concepto --}}
                    <td>
                        {{ $m->concepto->nombre ?? '—' }}
                    </td>

                    {{-- Forma de pago --}}
                    <td>

                        @if($m->formaPago)

                            {{ $m->formaPago->nombre }}

                        @else

                            —

                        @endif

                    </td>

                    {{-- Cuenta bancaria --}}
                    <td>

                        @if($m->cuentaBancaria)

                            {{ $m->cuentaBancaria->nombre }}

                            @if($m->cuentaBancaria->banco)

                                <small class="text-muted d-block">
                                    {{ $m->cuentaBancaria->banco->nombre }}
                                </small>

                            @endif

                        @else

                            —

                        @endif

                    </td>

                    {{-- Importe --}}
                    <td>

                        <strong>
                            ${{ number_format($m->importe, 2) }}
                        </strong>

                    </td>

                    {{-- Estado --}}
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
                        colspan="11"
                        class="text-center"
                    >

                        No existen resultados.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- Paginación --}}
    <div class="card-footer">

        {{ $movimientos->appends(request()->query())->links() }}

    </div>

</div>

@endsection