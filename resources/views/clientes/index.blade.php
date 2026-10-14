@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="container-fluid">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h1 class="m-0">
                <i class="fas fa-users mr-2"></i>
                Clientes
            </h1>

            <small class="text-muted">
                Administración de clientes, saldos y locales asignados
            </small>
        </div>

        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i>
            Nuevo cliente
        </a>

    </div>

    {{-- Tabla --}}
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Lista de clientes
            </h3>
        </div>

        <div class="card-body table-responsive p-0">

            <table class="table table-hover table-striped">

                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Cliente</th>
                        <th>Razón social</th>
                        <th>RFC</th>
                        <th class="text-center">Locales</th>
                        <th class="text-right">Saldo actual</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 180px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($clientes as $cliente)

                        @php
                            $saldoActual = (float) $cliente->saldoActual();
                        @endphp

                        <tr>

                            <td>
                                {{ $cliente->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ $cliente->nombre }}
                                </strong>

                                @if($cliente->correo)

                                    <div class="small text-muted">
                                        {{ $cliente->correo }}
                                    </div>

                                @endif

                            </td>

                            <td>
                                {{ $cliente->razon_social ?: '—' }}
                            </td>

                            <td>
                                {{ $cliente->rfc ?: '—' }}
                            </td>

                            <td class="text-center">

                                <span class="badge badge-info">
                                    {{ $cliente->unidades->count() }}
                                </span>

                            </td>

                            <td class="text-right">

                                @if($saldoActual > 0)

                                    <span class="badge badge-danger">
                                        Debe ${{ number_format($saldoActual, 2) }}
                                    </span>

                                @elseif($saldoActual < 0)

                                    <span class="badge badge-primary">
                                        A favor ${{ number_format(abs($saldoActual), 2) }}
                                    </span>

                                @else

                                    <span class="badge badge-success">
                                        Liquidado
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                @if($cliente->estado)

                                    <span class="badge badge-success">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge badge-secondary">
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('clientes.show', $cliente) }}"
                                    class="btn btn-sm btn-info"
                                    title="Ver cliente"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a
                                    href="{{ route('clientes.edit', $cliente) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Editar cliente"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($cliente->estado)

                                    <form
                                        action="{{ route('clientes.destroy', $cliente) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Está seguro de desactivar este cliente?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Desactivar cliente"
                                        >
                                            <i class="fas fa-ban"></i>
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-4">

                                <i class="fas fa-users fa-2x text-muted mb-2"></i>

                                <p class="mb-0">
                                    No hay clientes registrados.
                                </p>

                                <a
                                    href="{{ route('clientes.create') }}"
                                    class="btn btn-primary btn-sm mt-2"
                                >
                                    <i class="fas fa-plus"></i>
                                    Registrar primer cliente
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($clientes->hasPages())

            <div class="card-footer">
                {{ $clientes->links() }}
            </div>

        @endif

    </div>

</div>

@endsection