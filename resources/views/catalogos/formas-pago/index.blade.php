@extends('layouts.app')

@section('title', 'Formas de Pago')

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
                <a href="{{ route('formas-pago.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Forma de Pago
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
                            placeholder="Buscar...">

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
                    <th>Nombre</th>
                    <th width="100">Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($formasPago as $formaPago)

                <tr>
                    <td>{{ $formaPago->id }}</td>

                    <td>{{ $formaPago->nombre }}</td>

                    <td>
                        @if($formaPago->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-secondary">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('formas-pago.edit', $formaPago) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('formas-pago.destroy', $formaPago) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado de la forma de pago?')">

                                <i class="fas fa-sync-alt"></i>

                            </button>

                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        No existen registros.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $formasPago->appends(request()->query())->links() }}
    </div>

</div>

@endsection