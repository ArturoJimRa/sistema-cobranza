@extends('layouts.app')

@section('title','Editar Movimiento')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">
            Editar Movimiento - {{ $movimiento->folio }}
        </h3>
    </div>

    <form action="{{ route('movimientos.update', $movimiento) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('movimientos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/movimientos.js') }}"></script>
@endpush