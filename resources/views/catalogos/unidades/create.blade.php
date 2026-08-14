@extends('layouts.app')

@section('title','Nueva Unidad')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nueva Unidad</h3>
    </div>

    <form action="{{ route('unidades.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.unidades._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('unidades.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection