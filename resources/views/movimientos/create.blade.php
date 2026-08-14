@extends('layouts.app')

@section('title','Nuevo Movimiento')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Registrar Movimiento</h3>
    </div>

    <form action="{{ route('movimientos.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('movimientos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
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