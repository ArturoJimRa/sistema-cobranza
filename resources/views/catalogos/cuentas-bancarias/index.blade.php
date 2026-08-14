@extends('layouts.app')

@section('title', 'Cuentas Bancarias')

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
                <a href="{{ route('cuentas-bancarias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Cuenta
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
                            placeholder="Buscar por banco, cuenta, número o CLABE...">

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
                    <th>Banco</th>
                    <th>Cuenta</th>
                    <th>Número</th>
                    <th>CLABE</th>
                    <th width="100">Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($cuentas as $cuenta)

                <tr>
                    <td>{{ $cuenta->id }}</td>

                    <td>{{ $cuenta->banco->nombre ?? 'Sin banco' }}</td>

                    <td>{{ $cuenta->nombre }}</td>

                    <td>{{ $cuenta->numero_cuenta }}</td>

                    <td>{{ $cuenta->clabe }}</td>

                    <td>
                        @if($cuenta->estado)
                            <span class="badge badge-success">Activa</span>
                        @else
                            <span class="badge badge-secondary">Inactiva</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('cuentas-bancarias.edit', $cuenta) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form
                            action="{{ route('cuentas-bancarias.destroy', $cuenta) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-secondary btn-sm"
                                onclick="return confirm('¿Desea cambiar el estado de la cuenta bancaria?')">

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
        {{ $cuentas->appends(request()->query())->links() }}
    </div>

</div>

@endsection