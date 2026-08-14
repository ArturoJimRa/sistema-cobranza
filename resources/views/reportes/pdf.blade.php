<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Reporte de Movimientos</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h2,
        h4 {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            font-size: 18px;
        }

        .header h4 {
            font-size: 13px;
            margin-top: 4px;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 5px;
            vertical-align: middle;
        }

        .cancelado {
            background: #f8d7da;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
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

        .totales {
            width: 50%;
            margin-left: auto;
            margin-top: 10px;
        }

        .totales th {
            text-align: left;
        }

    </style>

</head>

<body>

    {{-- ====================================================== --}}
    {{-- ENCABEZADO --}}
    {{-- ====================================================== --}}

    <div class="header">

        <h2>
            Sistema Administrativo de Cobranza
        </h2>

        <h4>
            Reporte de Movimientos
        </h4>

    </div>


    {{-- ====================================================== --}}
    {{-- INFORMACIÓN DEL REPORTE --}}
    {{-- ====================================================== --}}

    <div class="info">

        <strong>
            Fecha de generación:
        </strong>

        {{ now()->format('d/m/Y H:i') }}

    </div>


    {{-- ====================================================== --}}
    {{-- TABLA DE MOVIMIENTOS --}}
    {{-- ====================================================== --}}

    <table>

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

                    <td>

                        @if($m->cuentaBancaria)

                            {{ $m->cuentaBancaria->nombre }}

                            @if($m->cuentaBancaria->banco)

                                <br>

                                <small>
                                    {{ $m->cuentaBancaria->banco->nombre }}
                                </small>

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


    {{-- ====================================================== --}}
    {{-- TOTALES --}}
    {{-- ====================================================== --}}

    <table class="totales">

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

</body>

</html>