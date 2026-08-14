@extends('layouts.app')

@section('title','Nuevo Tipo de Inmueble')

@section('content')

<div class="card card-primary">

    <div class="card-header">

        <h3 class="card-title">

            Nuevo Tipo de Inmueble

        </h3>

    </div>

    <form action="{{ route('tipos-inmueble.store') }}" method="POST">

        @csrf

        <div class="card-body">

            @include('catalogos.tipos-inmueble._form')

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">

                Guardar

            </button>

            <a href="{{ route('tipos-inmueble.index') }}"
               class="btn btn-secondary">

                Cancelar

            </a>

        </div>

    </form>

</div>

@endsection