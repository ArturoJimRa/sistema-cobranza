@extends('layouts.app')

@section('title', 'Movimientos')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('movimientos.ingresos.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Registrar Ingreso
                </a>

                <a href="{{ route('movimientos.gastos.create') }}" class="btn btn-danger">
                    <i class="fas fa-minus"></i> Registrar Gasto
                </a>
            </div>

            <div class="col-md-6">
                <form method="GET">
                    <div class="input-group">
                        <input type="text" name="buscar" class="form-control"
                               value="{{ request('buscar') }}"
                               placeholder="Buscar por folio o referencia...">
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
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Inmobiliaria</th>
                    <th>Inmueble</th>
                    <th>Unidad</th>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>Estado</th>
                    <th width="170">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($movimientos as $movimiento)
                <tr class="{{ $movimiento->estado == 2 ? 'table-danger' : '' }}">
                    <td>{{ $movimiento->folio }}</td>
                    <td>{{ $movimiento->fecha }}</td>
                    <td>
                        @if($movimiento->naturaleza == 1)
                            <span class="badge badge-success">Ingreso</span>
                        @else
                            <span class="badge badge-danger">Gasto</span>
                        @endif
                    </td>
                    <td>{{ $movimiento->inmobiliaria->nombre ?? '' }}</td>
                    <td>{{ $movimiento->inmueble->nombre ?? '' }}</td>
                    <td>{{ $movimiento->unidad->nombre ?? '' }}</td>
                    <td>{{ $movimiento->concepto->nombre ?? '' }}</td>
                    <td>${{ number_format($movimiento->importe, 2) }}</td>
                    <td>
                        @if($movimiento->estado == 1)
                            <span class="badge badge-success">Vigente</span>
                        @else
                            <span class="badge badge-danger">Cancelado</span>
                        @endif
                    </td>
                    <td>
                        @if($movimiento->estado == 1)
                            <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('movimientos.destroy', $movimiento) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-secondary btn-sm"
                                        onclick="return confirm('¿Desea cancelar este movimiento?')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted">Sin acciones</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No existen movimientos.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $movimientos->appends(request()->query())->links() }}
    </div>
</div>

@endsection