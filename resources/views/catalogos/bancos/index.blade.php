@extends('layouts.app')

@section('title', 'Bancos')

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
                <a href="{{ route('bancos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Banco
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

            @forelse($bancos as $banco)

                <tr>
                    <td>{{ $banco->id }}</td>

                    <td>{{ $banco->nombre }}</td>

                    <td>
                        @if($banco->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-secondary">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('bancos.edit', $banco) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('bancos.destroy', $banco) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado del banco?')">

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
        {{ $bancos->appends(request()->query())->links() }}
    </div>

</div>

@endsection