@extends('layouts.app')

@section('title','Editar Banco')

@section('content')

<div class="card card-warning">

    <div class="card-header">
        <h3 class="card-title">Editar Banco</h3>
    </div>

    <form action="{{ route('bancos.update', $banco) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('catalogos.bancos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
            </button>

            <a href="{{ route('bancos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection