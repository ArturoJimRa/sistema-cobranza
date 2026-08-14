@extends('layouts.app')

@section('title','Editar Unidad')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Unidad</h3>
    </div>

    <form action="{{ route('unidades.update', $unidad) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.unidades._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('unidades.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection