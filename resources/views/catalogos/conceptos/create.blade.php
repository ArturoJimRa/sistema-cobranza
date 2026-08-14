@extends('layouts.app')

@section('title','Nuevo Concepto')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nuevo Concepto</h3>
    </div>

    <form action="{{ route('conceptos.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.conceptos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('conceptos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection