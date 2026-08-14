@extends('layouts.app')

@section('title', 'Conceptos')

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

            <div class="col-md-4">
                <a href="{{ route('conceptos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Concepto
                </a>
            </div>

            <div class="col-md-8">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-5">
                            <select name="tipo" class="form-control">
                                <option value="">Todos los tipos</option>
                                <option value="1" {{ request('tipo') == 1 ? 'selected' : '' }}>
                                    Ingreso
                                </option>
                                <option value="2" {{ request('tipo') == 2 ? 'selected' : '' }}>
                                    Gasto
                                </option>
                            </select>
                        </div>

                        <div class="col-md-7">
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
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th width="100">Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($conceptos as $concepto)

                <tr>
                    <td>{{ $concepto->id }}</td>

                    <td>
                        @if($concepto->tipo == 1)
                            <span class="badge badge-success">Ingreso</span>
                        @else
                            <span class="badge badge-danger">Gasto</span>
                        @endif
                    </td>

                    <td>{{ $concepto->nombre }}</td>

                    <td>
                        @if($concepto->estado)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-secondary">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('conceptos.edit', $concepto) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('conceptos.destroy', $concepto) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado del concepto?')">

                                <i class="fas fa-sync-alt"></i>

                            </button>

                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No existen registros.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $conceptos->appends(request()->query())->links() }}
    </div>

</div>

@endsection