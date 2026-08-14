document.addEventListener('DOMContentLoaded', function () {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const inmuebleSelect = document.getElementById('inmueble_id');
    const unidadSelect = document.getElementById('unidad_id');

    const naturalezaSelect = document.getElementById('naturaleza');
    const conceptoSelect = document.getElementById('concepto_id');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const grupoReferencia = document.getElementById('grupo_referencia');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');
    const referenciaInput = document.getElementById('referencia');

    const inmuebleSeleccionadoInicial = inmuebleSelect?.value || '';
    const unidadSeleccionadaInicial = unidadSelect?.value || '';
    const conceptoSeleccionadoInicial = conceptoSelect?.value || '';

    const conceptosOriginales = conceptoSelect
        ? Array.from(conceptoSelect.options)
            .filter(option => option.value)
            .map(option => ({
                value: option.value,
                text: option.textContent.trim(),
                tipo: option.dataset.tipo || '',
            }))
        : [];

    function limpiarSelect(select, texto = 'Seleccione...') {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = texto;

        select.appendChild(option);
    }

    function agregarOpciones(select, data, seleccionado = '') {
        if (!select) {
            return;
        }

        data.forEach(item => {
            const option = document.createElement('option');

            option.value = item.id;
            option.textContent = item.nombre;

            if (String(item.id) === String(seleccionado)) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    }

    async function obtenerJson(url) {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}`);
        }

        return response.json();
    }

    async function cargarInmuebles(
        inmobiliariaId,
        inmuebleSeleccionado = ''
    ) {
        limpiarSelect(inmuebleSelect, 'Cargando inmuebles...');
        limpiarSelect(unidadSelect, 'Seleccione...');

        if (!inmobiliariaId) {
            limpiarSelect(
                inmuebleSelect,
                'Seleccione primero una inmobiliaria...'
            );

            return;
        }

        try {
            const inmuebles = await obtenerJson(
                `/ajax/inmuebles/${inmobiliariaId}`
            );

            limpiarSelect(inmuebleSelect, 'Seleccione...');

            agregarOpciones(
                inmuebleSelect,
                inmuebles,
                inmuebleSeleccionado
            );

            if (inmuebleSeleccionado) {
                await cargarUnidades(
                    inmuebleSeleccionado,
                    unidadSeleccionadaInicial
                );
            }
        } catch (error) {
            console.error(error);

            limpiarSelect(
                inmuebleSelect,
                'No fue posible cargar los inmuebles'
            );
        }
    }

    async function cargarUnidades(
        inmuebleId,
        unidadSeleccionada = ''
    ) {
        limpiarSelect(unidadSelect, 'Cargando unidades...');

        if (!inmuebleId) {
            limpiarSelect(unidadSelect, 'Seleccione...');
            return;
        }

        try {
            const unidades = await obtenerJson(
                `/ajax/unidades/${inmuebleId}`
            );

            limpiarSelect(unidadSelect, 'Seleccione...');

            agregarOpciones(
                unidadSelect,
                unidades,
                unidadSeleccionada
            );
        } catch (error) {
            console.error(error);

            limpiarSelect(
                unidadSelect,
                'No fue posible cargar las unidades'
            );
        }
    }

    function filtrarConceptosPorNaturaleza(
        conceptoSeleccionado = ''
    ) {
        if (!naturalezaSelect || !conceptoSelect) {
            return;
        }

        const naturaleza = naturalezaSelect.value;

        limpiarSelect(conceptoSelect, 'Seleccione...');

        conceptosOriginales
            .filter(concepto => {
                return !naturaleza || concepto.tipo === naturaleza;
            })
            .forEach(concepto => {
                const option = document.createElement('option');

                option.value = concepto.value;
                option.textContent = concepto.text;

                if (
                    String(concepto.value) ===
                    String(conceptoSeleccionado)
                ) {
                    option.selected = true;
                }

                conceptoSelect.appendChild(option);
            });

        const existeSeleccion = Array.from(conceptoSelect.options)
            .some(option => {
                return (
                    String(option.value) ===
                    String(conceptoSeleccionado)
                );
            });

        if (!existeSeleccion) {
            conceptoSelect.value = '';
        }
    }

    function controlarFormaPago() {
        if (!formaPagoSelect) {
            return;
        }

        const texto = formaPagoSelect
            .options[formaPagoSelect.selectedIndex]
            ?.textContent
            .trim()
            .toLowerCase() || '';

        const esEfectivo = texto.includes('efectivo');
        const esTransferencia = texto.includes('transferencia');

        if (grupoCuenta) {
            grupoCuenta.style.display = esTransferencia
                ? 'block'
                : 'none';
        }

        if (grupoReferencia) {
            grupoReferencia.style.display = esTransferencia
                ? 'block'
                : 'none';
        }

        if (esEfectivo || !esTransferencia) {
            if (cuentaSelect) {
                cuentaSelect.value = '';
            }

            if (referenciaInput) {
                referenciaInput.value = '';
            }
        }
    }

    inmobiliariaSelect?.addEventListener('change', function () {
        cargarInmuebles(this.value);
    });

    inmuebleSelect?.addEventListener('change', function () {
        cargarUnidades(this.value);
    });

    naturalezaSelect?.addEventListener('change', function () {
        filtrarConceptosPorNaturaleza();
    });

    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    if (inmobiliariaSelect?.value) {
        cargarInmuebles(
            inmobiliariaSelect.value,
            inmuebleSeleccionadoInicial
        );
    }

    filtrarConceptosPorNaturaleza(
        conceptoSeleccionadoInicial
    );

    controlarFormaPago();
});