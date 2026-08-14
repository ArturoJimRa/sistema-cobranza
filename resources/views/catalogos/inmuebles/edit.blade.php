@extends('layouts.app')

@section('title','Editar Inmueble')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Inmueble</h3>
    </div>

    <form action="{{ route('inmuebles.update', $inmueble) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.inmuebles._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('inmuebles.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection