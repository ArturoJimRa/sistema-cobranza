@extends('layouts.app')

@section('title', 'Exportar a Microsip')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-export mr-1"></i>
            Archivos VENTAF / VENTANF
        </h3>
    </div>

    <form method="GET" action="{{ route('microsip.index') }}">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha" class="form-control"
                           value="{{ $fecha->toDateString() }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Inmobiliaria</label>
                    <select name="inmobiliaria_id" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($inmobiliarias as $i)
                            <option value="{{ $i->id }}" {{ optional($inmobiliaria)->id == $i->id ? 'selected' : '' }}>
                                {{ $i->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-1"></i> Ver vista previa
                    </button>
                </div>

            </div>

            <small class="text-muted">
                Solo se incluyen <strong>ingresos vigentes</strong> de la fecha seleccionada.
                Los marcados como <strong>Facturado</strong> van en VENTAF; el resto en VENTANF.
            </small>

        </div>
    </form>

</div>

@if($inmobiliaria)

    @php
        $sinClave = $previas['F']['conceptos']->where('sin_clave', true);
    @endphp

    @if($sinClave->isNotEmpty())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Estos conceptos no tienen <strong>clave Microsip</strong> y se exportarán con su ID:
            {{ $sinClave->map(fn ($c) => $c['nombre'].' ('.$c['clave'].')')->join(', ') }}.
            <a href="{{ route('conceptos.index') }}">Capturar claves</a>
        </div>
    @endif

    <div class="row">

        @foreach(['F' => ['Facturado', 'success'], 'NF' => ['No facturado', 'secondary']] as $tipo => [$titulo, $color])
            @php $p = $previas[$tipo]; @endphp

            <div class="col-lg-6">
                <div class="card card-outline card-{{ $color }}">

                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $titulo }}
                            <small class="text-muted ml-1">{{ $p['archivo'] }}</small>
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th width="80">Clave</th>
                                    <th>Concepto</th>
                                    <th class="text-right">Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($p['conceptos'] as $c)
                                    <tr class="{{ $c['importe'] > 0 ? 'font-weight-bold' : 'text-muted' }}">
                                        <td>{{ $c['clave'] }}</td>
                                        <td>{{ $c['nombre'] }}</td>
                                        <td class="text-right">${{ number_format($c['importe'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-light">
                                    <th colspan="3">Formas de pago</th>
                                </tr>
                                @foreach($p['formas_pago'] as $f)
                                    <tr class="{{ $f['importe'] > 0 ? 'font-weight-bold' : 'text-muted' }}">
                                        <td></td>
                                        <td>{{ $f['nombre'] }}</td>
                                        <td class="text-right">${{ number_format($f['importe'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">
                                        Total ({{ $p['movimientos'] }} {{ $p['movimientos'] == 1 ? 'movimiento' : 'movimientos' }})
                                    </th>
                                    <th class="text-right">${{ number_format($p['total_conceptos'], 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card-footer">
                        @if(abs($p['total_conceptos'] - $p['total_formas_pago']) > 0.009)
                            <div class="text-danger mb-2">
                                <i class="fas fa-times-circle"></i>
                                Conceptos y formas de pago no cuadran.
                            </div>
                        @endif

                        <a class="btn btn-{{ $color }}"
                           href="{{ route('microsip.descargar', ['fecha' => $fecha->toDateString(), 'inmobiliaria_id' => $inmobiliaria->id, 'tipo' => $tipo]) }}">
                            <i class="fas fa-download mr-1"></i>
                            Descargar {{ $tipo === 'F' ? 'VENTAF' : 'VENTANF' }}
                        </a>

                        @if($p['movimientos'] === 0)
                            <small class="text-muted ml-2">Sin movimientos: el archivo saldrá en ceros.</small>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach

    </div>

@endif

@endsection
