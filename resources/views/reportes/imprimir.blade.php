<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Imprimir Reporte</title>

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>

        body {
            padding: 20px;
            font-size: 13px;
        }

        .cancelado {
            background: #f8d7da !important;
        }

        .ingreso {
            font-weight: bold;
        }

        .gasto {
            font-weight: bold;
        }

        .vigente {
            font-weight: bold;
        }

        .estado-cancelado {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .cuenta-bancaria {
            line-height: 1.2;
        }

        .banco {
            font-size: 11px;
            color: #666;
        }

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                font-size: 10px;
            }

            .table {
                font-size: 9px;
            }

            .table th,
            .table td {
                padding: 4px;
            }

            .cancelado {
                background: #f8d7da !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: landscape;
                margin: 10mm;
            }

        }

    </style>

</head>


<body>


{{-- ====================================================== --}}
{{-- BOTONES --}}
{{-- ====================================================== --}}

<div class="no-print mb-3">

    <button
        onclick="window.print()"
        class="btn btn-dark"
    >

        <i class="fas fa-print"></i>

        Imprimir

    </button>


    <button
        onclick="window.close()"
        class="btn btn-secondary"
    >

        Cerrar

    </button>

</div>



{{-- ====================================================== --}}
{{-- ENCABEZADO --}}
{{-- ====================================================== --}}

<div class="text-center mb-4">

    <h3>
        Sistema Administrativo de Cobranza
    </h3>

    <h5>
        Reporte de Movimientos
    </h5>

    <p>
        Fecha de generación:
        {{ now()->format('d/m/Y H:i') }}
    </p>

</div>



{{-- ====================================================== --}}
{{-- TABLA DE MOVIMIENTOS --}}
{{-- ====================================================== --}}

<div class="table-responsive">

    <table class="table table-bordered table-sm">

        <thead>

            <tr>

                <th>
                    Folio
                </th>

                <th>
                    Fecha
                </th>

                <th>
                    Tipo
                </th>

                <th>
                    Inmobiliaria
                </th>

                <th>
                    Inmueble
                </th>

                <th>
                    Unidad
                </th>

                <th>
                    Concepto
                </th>

                <th>
                    Forma de pago
                </th>

                <th>
                    Cuenta bancaria
                </th>

                <th>
                    Importe
                </th>

                <th>
                    Estado
                </th>

            </tr>

        </thead>


        <tbody>

            @php

                $totalIngresos = 0;

                $totalGastos = 0;

            @endphp


            @forelse($movimientos as $m)


                {{-- ========================================== --}}
                {{-- TOTALES --}}
                {{-- ========================================== --}}

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



                {{-- ========================================== --}}
                {{-- MOVIMIENTO --}}
                {{-- ========================================== --}}

                <tr class="{{ $m->estado == 2 ? 'cancelado' : '' }}">


                    {{-- Folio --}}

                    <td>
                        {{ $m->folio }}
                    </td>


                    {{-- Fecha --}}

                    <td>
                        {{ $m->fecha }}
                    </td>


                    {{-- Tipo --}}

                    <td class="text-center">

                        @if($m->naturaleza == 1)

                            <span class="ingreso">
                                Ingreso
                            </span>

                        @else

                            <span class="gasto">
                                Gasto
                            </span>

                        @endif

                    </td>


                    {{-- Inmobiliaria --}}

                    <td>
                        {{ $m->inmobiliaria->nombre ?? '—' }}
                    </td>


                    {{-- Inmueble --}}

                    <td>
                        {{ $m->inmueble->nombre ?? '—' }}
                    </td>


                    {{-- Unidad --}}

                    <td>
                        {{ $m->unidad->nombre ?? '—' }}
                    </td>


                    {{-- Concepto --}}

                    <td>
                        {{ $m->concepto->nombre ?? '—' }}
                    </td>


                    {{-- ====================================== --}}
                    {{-- FORMA DE PAGO --}}
                    {{-- ====================================== --}}

                    <td>

                        @if($m->formaPago)

                            {{ $m->formaPago->nombre }}

                        @else

                            —

                        @endif

                    </td>


                    {{-- ====================================== --}}
                    {{-- CUENTA BANCARIA --}}
                    {{-- ====================================== --}}

                    <td class="cuenta-bancaria">

                        @if($m->cuentaBancaria)

                            {{ $m->cuentaBancaria->nombre }}

                            @if($m->cuentaBancaria->banco)

                                <br>

                                <span class="banco">

                                    {{ $m->cuentaBancaria->banco->nombre }}

                                </span>

                            @endif

                        @else

                            —

                        @endif

                    </td>


                    {{-- ====================================== --}}
                    {{-- IMPORTE --}}
                    {{-- ====================================== --}}

                    <td class="text-right">

                        ${{ number_format($m->importe, 2) }}

                    </td>


                    {{-- ====================================== --}}
                    {{-- ESTADO --}}
                    {{-- ====================================== --}}

                    <td class="text-center">

                        @if($m->estado == 1)

                            <span class="vigente">
                                Vigente
                            </span>

                        @else

                            <span class="estado-cancelado">
                                Cancelado
                            </span>

                        @endif

                    </td>


                </tr>


            @empty

                <tr>

                    <td
                        colspan="11"
                        class="text-center"
                    >

                        No existen resultados.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>



{{-- ====================================================== --}}
{{-- TOTALES --}}
{{-- ====================================================== --}}

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

                    <strong>
                        ${{ number_format($totalIngresos - $totalGastos, 2) }}
                    </strong>

                </td>

            </tr>

        </table>

    </div>

</div>



{{-- ====================================================== --}}
{{-- IMPRESIÓN AUTOMÁTICA --}}
{{-- ====================================================== --}}

<script>

    window.onload = function () {

        window.print();

    };

</script>


</body>

</html>