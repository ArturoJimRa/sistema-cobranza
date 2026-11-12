<?php

/*
|--------------------------------------------------------------------------
| Exportación a Microsip (VENTAF / VENTANF)
|--------------------------------------------------------------------------
|
| Textos fijos y reglas del layout. Si contabilidad pide otro texto en el
| encabezado o filas extra, se cambia aquí sin tocar el código.
|
*/

return [

    // Fila 1, columna B
    'encabezado_tipo' => env('MICROSIP_ENCABEZADO_TIPO', 'VENTAS MOSTRADOR'),

    // Fila 1, columna D
    'encabezado_cajero' => env('MICROSIP_ENCABEZADO_CAJERO', 'CAJERO'),

    // Formato de la fecha en la celda A1 (se escribe como texto)
    'formato_fecha' => 'd/m/Y',

    // Prefijos del nombre de archivo
    'prefijo_facturado' => 'VENTAF',
    'prefijo_no_facturado' => 'VENTANF',

    // Filas que van al final del bloque de formas de pago, siempre en 0
    // (el sistema de cobranza no maneja descuentos).
    'filas_extra' => [
        'DESCUENTOS',
    ],

    // Formato contable igual al del archivo original
    'formato_importe' => '_(* #,##0.00_);_(* \(#,##0.00\);_(* \-??_);_(@_)',

];
