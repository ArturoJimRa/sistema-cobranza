@extends('layouts.app')

@section('title', 'Inmuebles')

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
                <a href="{{ route('inmuebles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Inmueble
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
                            placeholder="Buscar por inmueble, inmobiliaria o tipo...">

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
                    <th>Inmobiliaria</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th width="100">Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($inmuebles as $inmueble)

                <tr>
                    <td>{{ $inmueble->id }}</td>

                    <td>{{ $inmueble->inmobiliaria->nombre ?? 'Sin inmobiliaria' }}</td>

                    <td>{{ $inmueble->tipoInmueble->nombre ?? 'Sin tipo' }}</td>

                    <td>{{ $inmueble->nombre }}</td>

                    <td>{{ $inmueble->direccion }}</td>

                    <td>
                        @if($inmueble->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-secondary">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('inmuebles.edit', $inmueble) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('inmuebles.destroy', $inmueble) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado del inmueble?')">

                                <i class="fas fa-sync-alt"></i>

                            </button>

                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center">
                        No existen registros.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $inmuebles->appends(request()->query())->links() }}
    </div>

</div>

@endsection