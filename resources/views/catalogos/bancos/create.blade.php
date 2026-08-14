@extends('layouts.app')

@section('title','Nuevo Banco')

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Nuevo Banco</h3>
    </div>

    <form action="{{ route('bancos.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('catalogos.bancos._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>

            <a href="{{ route('bancos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>

</div>

@endsection