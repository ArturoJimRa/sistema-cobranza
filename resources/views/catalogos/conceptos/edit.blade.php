@extends('layouts.app')

@section('title','Editar Concepto')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Concepto</h3>
    </div>

    <form action="{{ route('conceptos.update', $concepto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.conceptos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('conceptos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection