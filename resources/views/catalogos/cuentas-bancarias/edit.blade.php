@extends('layouts.app')

@section('title','Editar Cuenta Bancaria')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Cuenta Bancaria</h3>
    </div>

    <form action="{{ route('cuentas-bancarias.update', $cuenta) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.cuentas-bancarias._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('cuentas-bancarias.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection