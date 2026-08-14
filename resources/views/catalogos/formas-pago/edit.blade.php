@extends('layouts.app')

@section('title','Editar Forma de Pago')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Forma de Pago</h3>
    </div>

    <form action="{{ route('formas-pago.update', $formaPago) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.formas-pago._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('formas-pago.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection