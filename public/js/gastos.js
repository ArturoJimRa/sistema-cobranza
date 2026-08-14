document.addEventListener('DOMContentLoaded', () => {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const inmuebleSelect = document.getElementById('inmueble_id');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');

    const grupoReferencia = document.getElementById('grupo_referencia');
    const referenciaInput = document.getElementById('referencia');

    const inmuebleSeleccionado = inmuebleSelect?.dataset.selected || '';

    function limpiarSelect(select, texto) {
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

    async function cargarInmuebles(inmobiliariaId, seleccionado = '') {
        limpiarSelect(inmuebleSelect, 'Cargando inmuebles...');

        if (!inmobiliariaId) {
            limpiarSelect(
                inmuebleSelect,
                'Seleccione primero una inmobiliaria...'
            );

            return;
        }

        try {
            const response = await fetch(
                `/ajax/inmuebles/${inmobiliariaId}`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`Error HTTP ${response.status}`);
            }

            const inmuebles = await response.json();

            limpiarSelect(inmuebleSelect, 'Seleccione un inmueble...');
            agregarOpciones(inmuebleSelect, inmuebles, seleccionado);
        } catch (error) {
            console.error(error);

            limpiarSelect(
                inmuebleSelect,
                'No fue posible cargar los inmuebles'
            );
        }
    }

    function controlarFormaPago() {
        const texto = formaPagoSelect
            ?.options[formaPagoSelect.selectedIndex]
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

    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    if (inmobiliariaSelect?.value) {
        cargarInmuebles(
            inmobiliariaSelect.value,
            inmuebleSeleccionado
        );
    }

    controlarFormaPago();
});