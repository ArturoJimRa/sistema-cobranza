@extends('layouts.app')

@section('title', 'Inmobiliarias')

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
                <a href="{{ route('inmobiliarias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nueva Inmobiliaria
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

        <table class="table table-hover">

            <thead>

                <tr>

                    <th width="70">ID</th>

                    <th>Nombre</th>

                    <th>Razón Social</th>

                    <th>RFC</th>

                    <th>Teléfono</th>

                    <th>Correo</th>

                    <th width="100">Estado</th>

                    <th width="180">Acciones</th>

                </tr>

            </thead>

            <tbody>

            @forelse($inmobiliarias as $item)

                <tr>

                    <td>{{ $item->id }}</td>

                    <td>{{ $item->nombre }}</td>

                    <td>{{ $item->razon_social }}</td>

                    <td>{{ $item->rfc }}</td>

                    <td>{{ $item->telefono }}</td>

                    <td>{{ $item->correo }}</td>

                    <td>

                        @if($item->estado)

                            <span class="badge badge-success">
                                Activa
                            </span>

                        @else

                            <span class="badge badge-secondary">
                                Inactiva
                            </span>

                        @endif

                    </td>

                    <td>

                        <a
                            href="{{ route('inmobiliarias.edit',$item) }}"
                            class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>

                        </a>

                        <form
                            action="{{ route('inmobiliarias.destroy',$item) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado de esta inmobiliaria?')">

                                <i class="fas fa-sync"></i>

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center">

                        No existen registros.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        {{ $inmobiliarias->links() }}

    </div>

</div>

@endsection