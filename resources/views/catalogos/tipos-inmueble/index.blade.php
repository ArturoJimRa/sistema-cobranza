@extends('layouts.app')

@section('title', 'Tipos de Inmueble')

@section('content')

<div class="container-fluid">

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

                    <a href="{{ route('tipos-inmueble.create') }}"
                       class="btn btn-primary">

                        <i class="fas fa-plus"></i>

                        Nuevo Tipo

                    </a>

                </div>

                <div class="col-md-6">

                    <form method="GET">

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Buscar..."
                                name="buscar"
                                value="{{ request('buscar') }}">

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

                        <th>Descripción</th>

                        <th width="100">Estado</th>

                        <th width="170">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($tipos as $tipo)

                    <tr>

                        <td>

                            {{ $tipo->id }}

                        </td>

                        <td>

                            {{ $tipo->nombre }}

                        </td>

                        <td>

                            {{ $tipo->descripcion }}

                        </td>

                        <td>

                            @if($tipo->estado)

                                <span class="badge badge-success">

                                    Activo

                                </span>

                            @else

                                <span class="badge badge-secondary">

                                    Inactivo

                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('tipos-inmueble.edit',$tipo) }}"
                                class="btn btn-warning btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                            <form
                                action="{{ route('tipos-inmueble.destroy',$tipo) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-secondary btn-sm"
                                    onclick="return confirm('¿Desea cambiar el estado del registro?')">

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

        <div class="card-footer clearfix">

            {{ $tipos->links() }}

        </div>

    </div>

</div>

@endsection