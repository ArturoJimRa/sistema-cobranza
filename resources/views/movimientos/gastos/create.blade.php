@extends('layouts.app')

@section('title', 'Registrar Gasto')

@section('content')

<div class="card card-danger">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-arrow-circle-up mr-1"></i>
            Captura de gasto
        </h3>
    </div>

    <form
        action="{{ route('movimientos.gastos.store') }}"
        method="POST">

        @csrf

        <div class="card-body">

            <div class="alert alert-light border">
                Capture los datos correspondientes al gasto realizado.
            </div>

            @include('movimientos.gastos._form')

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-danger">
                <i class="fas fa-save mr-1"></i>
                Guardar gasto
            </button>

            <a
                href="{{ route('movimientos.index') }}"
                class="btn btn-secondary">

                Cancelar

            </a>

        </div>

    </form>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/gastos.js') }}"></script>
@endpush