@extends('layouts.app')

@section('title', 'Registrar Ingreso')

@section('content')

<div class="card card-success">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-arrow-circle-down mr-1"></i>
            Captura de ingreso
        </h3>
    </div>

    <form
        action="{{ route('movimientos.ingresos.store') }}"
        method="POST">

        @csrf

        <div class="card-body">

            <div class="alert alert-light border">
                Elija la <strong>inmobiliaria</strong>, el <strong>cliente</strong>
                que realizó el pago y el <strong>local</strong> al que corresponde.
                Si el ingreso no es de ningún cliente, elija
                <em>Sin cliente (ingreso general)</em> y después el inmueble.
            </div>

            @include('movimientos.ingresos._form')

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save mr-1"></i>
                Guardar ingreso
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
<script src="{{ asset('js/ingresos.js') }}?v={{ filemtime(public_path('js/ingresos.js')) }}"></script>
@endpush