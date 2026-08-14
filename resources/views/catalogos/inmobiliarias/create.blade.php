@extends('layouts.app')

@section('title','Nueva Inmobiliaria')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Registrar Inmobiliaria</h3>
    </div>

    <form action="{{ route('inmobiliarias.store') }}" method="POST">

        @csrf

        <div class="card-body">

            @include('catalogos.inmobiliarias._form')

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">
                <i class="fas fa-save"></i>
                Guardar
            </button>

            <a href="{{ route('inmobiliarias.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection