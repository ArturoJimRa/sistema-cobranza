@extends('layouts.app')

@section('title','Editar Tipo de Inmueble')

@section('content')

<div class="card card-warning">

    <div class="card-header">

        <h3 class="card-title">

            Editar Tipo de Inmueble

        </h3>

    </div>

    <form action="{{ route('tipos-inmueble.update',$tipo) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="card-body">

            @include('catalogos.tipos-inmueble._form')

        </div>

        <div class="card-footer">

            <button class="btn btn-warning">

                Actualizar

            </button>

            <a href="{{ route('tipos-inmueble.index') }}"
               class="btn btn-secondary">

                Cancelar

            </a>

        </div>

    </form>

</div>

@endsection