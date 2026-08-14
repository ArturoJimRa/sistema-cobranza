@extends('layouts.app')

@section('title','Nueva Cuenta Bancaria')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nueva Cuenta Bancaria</h3>
    </div>

    <form action="{{ route('cuentas-bancarias.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.cuentas-bancarias._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('cuentas-bancarias.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection