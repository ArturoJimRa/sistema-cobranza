<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Imprimir Reporte</title>

    <link rel="stylesheet"
          href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        body {
            padding: 20px;
            font-size: 12px;
        }

        .cancelado {
            background: #f8d7da !important;
        }

        .table {
            font-size: 11px;
        }

        .table th {
            white-space: nowrap;
        }

        .text-right {
            text-align: right;
        }

        @media print {

            @page {
                size: landscape;
                margin: 10mm;
            }

            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
            }

            .table {
                font-size: 9px;
            }
        }
    </style>

</head>

<body>

    {{-- BOTONES --}}
    <div class="no-print mb-3">

        <button onclick="window.print()" class="btn btn-dark">
            <i class="fas fa-print"></i>
            Imprimir
        </button>

        <button onclick="window.close()" class="btn btn-secondary">
            Cerrar
        </button>

    </div>


    {{-- ENCABEZADO --}}
    <div class="text-center mb-4">

        <h3>Sistema Administrativo de Cobranza</h3>

        <h5>Reporte de Movimientos</h5>

        <p class="mb-0">
            Fecha de generación:
            {{ now()->format('d/m/Y H:i') }}
        </p>

    </div>


    {{-- TABLA --}}
    <div class="table-responsive">

        <table class="table table-bordered table-sm">

            <thead>

                <tr>

                    <th>Folio</th>

                    <th>Fecha</th>

                    <th>Tipo</th>

                    <th>Cliente</th>

                    <th>Inmobiliaria</th>

                    <th>Inmueble</th>

                    <th>Local / Unidad</th>

                    <th>Concepto</th>

                    <th>Forma de pago</th>

                    <th>Cuenta bancaria</th>

                    <th class="text-right">Importe</th>

                    <th>Estado</th>

                </tr>

            </thead>


            <tbody>

                @php
                    $totalIngresos = 0;
                    $totalGastos = 0;
                @endphp


                @forelse($movimientos as $m)

                    {{-- SUMAMOS SOLAMENTE MOVIMIENTOS VIGENTES --}}
                    @if($m->estado == 1)

                        @if($m->naturaleza == 1)

                            @php
                                $totalIngresos += $m->importe;
                            @endphp

                        @else

                            @php
                                $totalGastos += $m->importe;
                            @endphp

                        @endif

                    @endif


                    <tr class="{{ $m->estado == 2 ? 'cancelado' : '' }}">

                        {{-- FOLIO --}}
                        <td>
                            {{ $m->folio }}
                        </td>


                        {{-- FECHA --}}
                        <td>
                            {{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}
                        </td>


                        {{-- TIPO --}}
                        <td>
                            {{ $m->naturaleza == 1 ? 'Ingreso' : 'Gasto' }}
                        </td>


                        {{-- CLIENTE --}}
                        <td>
                            {{ $m->unidad->cliente->nombre ?? 'Sin cliente' }}
                        </td>


                        {{-- INMOBILIARIA --}}
                        <td>
                            {{ $m->inmobiliaria->nombre ?? '' }}
                        </td>


                        {{-- INMUEBLE --}}
                        <td>
                            {{ $m->inmueble->nombre ?? '' }}
                        </td>


                        {{-- LOCAL / UNIDAD --}}
                        <td>
                            {{ $m->unidad->nombre ?? '' }}
                        </td>


                        {{-- CONCEPTO --}}
                        <td>
                            {{ $m->concepto->nombre ?? '' }}
                        </td>


                        {{-- FORMA DE PAGO --}}
                        <td>
                            {{ $m->formaPago->nombre ?? '' }}
                        </td>


                        {{-- CUENTA BANCARIA --}}
                        <td>

                            @if($m->cuentaBancaria)

                                {{ $m->cuentaBancaria->nombre ?? '' }}

                                @if($m->cuentaBancaria->banco)

                                    <br>

                                    <small>
                                        {{ $m->cuentaBancaria->banco->nombre }}
                                    </small>

                                @endif

                            @else

                                <span class="text-muted">
                                    Efectivo / No aplica
                                </span>

                            @endif

                        </td>


                        {{-- IMPORTE --}}
                        <td class="text-right">

                            ${{ number_format($m->importe, 2) }}

                        </td>


                        {{-- ESTADO --}}
                        <td>

                            @if($m->estado == 1)

                                Vigente

                            @else

                                Cancelado

                            @endif

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="12" class="text-center">

                            No existen movimientos para mostrar.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- TOTALES --}}
    <div class="row mt-4">

        <div class="col-md-4 offset-md-8">

            <table class="table table-bordered table-sm">

                <tr>

                    <th>
                        Total Ingresos
                    </th>

                    <td class="text-right">

                        ${{ number_format($totalIngresos, 2) }}

                    </td>

                </tr>


                <tr>

                    <th>
                        Total Gastos
                    </th>

                    <td class="text-right">

                        ${{ number_format($totalGastos, 2) }}

                    </td>

                </tr>


                <tr>

                    <th>
                        Total Neto
                    </th>

                    <td class="text-right">

                        ${{ number_format($totalIngresos - $totalGastos, 2) }}

                    </td>

                </tr>

            </table>

        </div>

    </div>


    <script>

        window.onload = function () {
            window.print();
        };

    </script>

</body>

</html>