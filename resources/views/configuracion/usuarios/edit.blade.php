@extends('layouts.app')

@section('title','Editar Usuario')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Editar Usuario</h3>
    </div>

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('configuracion.usuarios._form')
        </div>
        <div class="card-footer">
            <button class="btn btn-warning"><i class="fas fa-save"></i> Actualizar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection