@extends('layouts.app')

@section('title', 'Unidades')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

<div class="card">

    <div class="card-header">

        <div class="row">

            <div class="col-md-6">
                <a href="{{ route('unidades.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Unidad
                </a>
            </div>

            <div class="col-md-6">
                <form method="GET">
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            name="buscar"
                            value="{{ request('buscar') }}"
                            placeholder="Buscar por unidad, razón social o inmueble...">

                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-striped">

            <thead>
                <tr>
                    <th width="70">ID</th>
                    <th>Inmueble</th>
                    <th>Unidad</th>
                    <th>Razón Social</th>
                    <th width="100">Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($unidades as $unidad)

                <tr>
                    <td>{{ $unidad->id }}</td>

                    <td>{{ $unidad->inmueble->nombre ?? 'Sin inmueble' }}</td>

                    <td>{{ $unidad->nombre }}</td>

                    <td>{{ $unidad->razon_social }}</td>

                    <td>
                        @if($unidad->estado)
                            <span class="badge badge-success">Activa</span>
                        @else
                            <span class="badge badge-secondary">Inactiva</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('unidades.edit', $unidad) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('unidades.destroy', $unidad) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado de la unidad?')">

                                <i class="fas fa-sync-alt"></i>

                            </button>

                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No existen registros.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $unidades->appends(request()->query())->links() }}
    </div>

</div>

@endsection