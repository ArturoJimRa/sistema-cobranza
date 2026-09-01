@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h1 class="m-0">
                <i class="fas fa-user-plus mr-2"></i>
                Nuevo cliente
            </h1>

            <small class="text-muted">
                Registrar un nuevo cliente y asignar sus locales
            </small>
        </div>

        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Regresar
        </a>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Revise los siguientes errores:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route('clientes.store') }}"
    >

        @csrf

        {{-- Datos del cliente --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Datos del cliente
                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>
                            Nombre del cliente
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            value="{{ old('nombre') }}"
                            placeholder="Nombre del cliente"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>
                            Razón social
                        </label>

                        <input
                            type="text"
                            name="razon_social"
                            class="form-control"
                            value="{{ old('razon_social') }}"
                            placeholder="Razón social"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>
                            RFC
                        </label>

                        <input
                            type="text"
                            name="rfc"
                            class="form-control"
                            value="{{ old('rfc') }}"
                            placeholder="RFC"
                            maxlength="20"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            class="form-control"
                            value="{{ old('telefono') }}"
                            placeholder="Teléfono"
                            maxlength="30"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="correo"
                            class="form-control"
                            value="{{ old('correo') }}"
                            placeholder="correo@empresa.com"
                        >

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>
                            Dirección
                        </label>

                        <textarea
                            name="direccion"
                            class="form-control"
                            rows="3"
                            placeholder="Dirección del cliente"
                        >{{ old('direccion') }}</textarea>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>
                            Estado
                        </label>

                        <div class="custom-control custom-switch">

                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="estado"
                                name="estado"
                                value="1"
                                {{ old('estado', 1) ? 'checked' : '' }}
                            >

                            <label
                                class="custom-control-label"
                                for="estado"
                            >
                                Cliente activo
                            </label>

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

            </div>

            <div class="card-body">

                @if($unidades->count())

                    <div class="alert alert-info">

                        <i class="fas fa-info-circle mr-1"></i>

                        Seleccione los locales que pertenecen a este cliente.
                        Puede seleccionar uno o varios.

                    </div>

                    <div class="row">

                        @foreach($unidades as $unidad)

                            <div class="col-md-6 col-lg-4 mb-3">

                                <div class="card card-outline card-secondary h-100">

                                    <div class="card-body">

                                        <div class="custom-control custom-checkbox">

                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="unidad_{{ $unidad->id }}"
                                                name="unidades[]"
                                                value="{{ $unidad->id }}"
                                                {{ in_array($unidad->id, old('unidades', [])) ? 'checked' : '' }}
                                            >

                                            <label
                                                class="custom-control-label"
                                                for="unidad_{{ $unidad->id }}"
                                            >

                                                <strong>
                                                    {{ $unidad->nombre }}
                                                </strong>

                                            </label>

                                        </div>

                                        <div class="mt-2 text-muted small">

                                            @if($unidad->inmueble)

                                                <i class="fas fa-building mr-1"></i>

                                                {{ $unidad->inmueble->nombre }}

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-exclamation-triangle mr-1"></i>

                        Actualmente no existen locales disponibles para asignar.

                    </div>

                @endif

            </div>

        </div>

        {{-- Botones --}}
        <div class="card">

            <div class="card-body text-right">

                <a
                    href="{{ route('clientes.index') }}"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-times mr-1"></i>
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-save mr-1"></i>
                    Guardar cliente
                </button>

            </div>

        </div>

    </form>

</div>

@endsection