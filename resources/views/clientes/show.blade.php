@extends('layouts.app')

@section('title', 'Detalle del cliente')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h1 class="m-0">
                <i class="fas fa-user mr-2"></i>
                Detalle del cliente
            </h1>

            <small class="text-muted">
                Información general y locales asignados
            </small>
        </div>

        <div>

            <a
                href="{{ route('clientes.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left mr-1"></i>
                Regresar
            </a>

            <a
                href="{{ route('clientes.edit', $cliente) }}"
                class="btn btn-warning"
            >
                <i class="fas fa-edit mr-1"></i>
                Editar
            </a>

        </div>

    </div>

    {{-- Información del cliente --}}
    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-id-card mr-1"></i>
                Información del cliente
            </h3>

            <div class="card-tools">

                @if($cliente->estado)

                    <span class="badge badge-success">
                        Activo
                    </span>

                @else

                    <span class="badge badge-secondary">
                        Inactivo
                    </span>

                @endif

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>
                        Nombre del cliente
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->nombre }}
                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <strong>
                        Razón social
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->razon_social ?: 'No registrada' }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>
                        RFC
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->rfc ?: 'No registrado' }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>
                        Teléfono
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->telefono ?: 'No registrado' }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>
                        Correo electrónico
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->correo ?: 'No registrado' }}
                    </div>

                </div>

                <div class="col-md-12">

                    <strong>
                        Dirección
                    </strong>

                    <div class="text-muted">
                        {{ $cliente->direccion ?: 'No registrada' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Locales --}}
    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-store mr-1"></i>
                Locales asignados
            </h3>

            <div class="card-tools">

                <span class="badge badge-info">
                    {{ $cliente->unidades->count() }}
                    {{ $cliente->unidades->count() == 1 ? 'local' : 'locales' }}
                </span>

            </div>

        </div>

        <div class="card-body">

            @if($cliente->unidades->count())

                <div class="row">

                    @foreach($cliente->unidades as $unidad)

                        <div class="col-md-6 col-lg-4 mb-3">

                            <div class="card card-outline card-primary h-100">

                                <div class="card-body">

                                    <h5 class="mb-2">

                                        <i class="fas fa-store mr-1"></i>

                                        {{ $unidad->nombre }}

                                    </h5>

                                    @if($unidad->inmueble)

                                        <p class="text-muted mb-2">

                                            <i class="fas fa-building mr-1"></i>

                                            {{ $unidad->inmueble->nombre }}

                                        </p>

                                    @endif

                                    @if($unidad->razon_social)

                                        <small class="text-muted">

                                            Razón social:
                                            {{ $unidad->razon_social }}

                                        </small>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="fas fa-exclamation-triangle mr-1"></i>

                    Este cliente no tiene locales asignados actualmente.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection