document.addEventListener('DOMContentLoaded', () => {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const inmuebleSelect = document.getElementById('inmueble_id');

    const grupoUnidad = document.getElementById('grupo_unidad');
    const unidadSelect = document.getElementById('unidad_id');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');

    const grupoReferencia = document.getElementById('grupo_referencia');
    const referenciaInput = document.getElementById('referencia');

    const inmuebleSeleccionado = inmuebleSelect?.dataset.selected || '';
    const unidadSeleccionada = unidadSelect?.dataset.selected || '';

    function limpiarSelect(select, texto) {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = texto;

        select.appendChild(option);
    }

    function agregarOpciones(select, registros, seleccionado = '') {
        registros.forEach((registro) => {
            const option = document.createElement('option');

            option.value = registro.id;
            option.textContent = registro.nombre;
            option.selected = String(registro.id) === String(seleccionado);

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

    async function cargarInmuebles(inmobiliariaId, seleccionado = '') {
        limpiarSelect(inmuebleSelect, 'Cargando inmuebles...');
        limpiarSelect(unidadSelect, 'Seleccione un local...');

        grupoUnidad.style.display = 'none';
        unidadSelect.value = '';

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

            limpiarSelect(inmuebleSelect, 'Seleccione un inmueble...');

            agregarOpciones(
                inmuebleSelect,
                inmuebles,
                seleccionado
            );

            if (seleccionado) {
                await cargarUnidades(seleccionado, unidadSeleccionada);
            }
        } catch (error) {
            console.error(error);

            limpiarSelect(
                inmuebleSelect,
                'No fue posible cargar los inmuebles'
            );
        }
    }

    async function cargarUnidades(inmuebleId, seleccionado = '') {
        limpiarSelect(unidadSelect, 'Cargando locales...');
        grupoUnidad.style.display = 'none';

        if (!inmuebleId) {
            unidadSelect.value = '';
            return;
        }

        try {
            const unidades = await obtenerJson(
                `/ajax/unidades/${inmuebleId}`
            );

            limpiarSelect(unidadSelect, 'Seleccione un local...');

            if (unidades.length === 0) {
                unidadSelect.value = '';
                grupoUnidad.style.display = 'none';
                return;
            }

            agregarOpciones(unidadSelect, unidades, seleccionado);
            grupoUnidad.style.display = 'block';
        } catch (error) {
            console.error(error);

            limpiarSelect(
                unidadSelect,
                'No fue posible cargar los locales'
            );
        }
    }

    function controlarFormaPago() {
        if (!formaPagoSelect) {
            return;
        }

        const texto = formaPagoSelect
            .options[formaPagoSelect.selectedIndex]
            ?.text
            .trim()
            .toLowerCase() || '';

        const esTransferencia = texto.includes('transferencia');

        grupoCuenta.style.display = esTransferencia ? 'block' : 'none';
        grupoReferencia.style.display = esTransferencia ? 'block' : 'none';

        if (!esTransferencia) {
            cuentaSelect.value = '';
            referenciaInput.value = '';
        }
    }

    inmobiliariaSelect?.addEventListener('change', function () {
        cargarInmuebles(this.value);
    });

    inmuebleSelect?.addEventListener('change', function () {
        cargarUnidades(this.value);
    });

    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    if (inmobiliariaSelect?.value) {
        cargarInmuebles(
            inmobiliariaSelect.value,
            inmuebleSeleccionado
        );
    }

    controlarFormaPago();
});