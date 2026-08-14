@extends('layouts.app')

@section('title','Nuevo Usuario')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Nuevo Usuario</h3>
    </div>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('configuracion.usuarios._form')
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection