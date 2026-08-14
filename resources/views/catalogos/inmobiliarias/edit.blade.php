@extends('layouts.app')

@section('title','Editar Inmobiliaria')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Inmobiliaria</h3>
    </div>

    <form action="{{ route('inmobiliarias.update',$inmobiliaria) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="card-body">

            @include('catalogos.inmobiliarias._form')

        </div>

        <div class="card-footer">

            <button class="btn btn-warning">

                <i class="fas fa-save"></i>

                Actualizar

            </button>

            <a href="{{ route('inmobiliarias.index') }}" class="btn btn-secondary">

                Cancelar

            </a>

        </div>

    </form>

</div>

@endsection