@extends('layouts.app')

@section('title','Nueva Forma de Pago')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nueva Forma de Pago</h3>
    </div>

    <form action="{{ route('formas-pago.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.formas-pago._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('formas-pago.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection