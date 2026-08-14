@extends('layouts.app')

@section('title','Nuevo Inmueble')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nuevo Inmueble</h3>
    </div>

    <form action="{{ route('inmuebles.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.inmuebles._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('inmuebles.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection