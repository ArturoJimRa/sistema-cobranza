document.addEventListener('DOMContentLoaded', () => {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const clienteSelect = document.getElementById('cliente_id');

    const grupoUnidad = document.getElementById('grupo_unidad');
    const unidadSelect = document.getElementById('unidad_id');
    const infoLocal = document.getElementById('info_local');

    const grupoInmueble = document.getElementById('grupo_inmueble');
    const inmuebleSelect = document.getElementById('inmueble_id');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');
    const grupoReferencia = document.getElementById('grupo_referencia');
    const referenciaInput = document.getElementById('referencia');

    const unidadSeleccionada = unidadSelect?.dataset.selected || '';
    const inmuebleSeleccionado = inmuebleSelect?.dataset.selected || '';

    function limpiarSelect(select, texto) {
        if (!select) return;
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = texto;
        select.appendChild(option);
    }

    async function obtenerJson(url) {
        const response = await fetch(url, { headers: { Accept: 'application/json' } });
        if (!response.ok) throw new Error(`Error HTTP ${response.status}`);
        return response.json();
    }

    function unidadesDe(option) {
        try {
            return JSON.parse(option?.dataset.unidades || '[]');
        } catch (e) {
            return [];
        }
    }

    /* Locales del cliente elegido, solo de la inmobiliaria elegida */
    function localesDelCliente() {
        const inmo = inmobiliariaSelect.value;
        return unidadesDe(clienteSelect.options[clienteSelect.selectedIndex])
            .filter((u) => String(u.inmobiliaria_id) === String(inmo));
    }

    /* ---------- Inmobiliaria -> clientes con locales en ella ---------- */
    function filtrarClientes() {
        const inmo = inmobiliariaSelect.value;
        let visibles = 0;

        [...clienteSelect.options].forEach((option) => {
            if (option.value === '') {
                option.textContent = inmo ? 'Seleccione un cliente...' : 'Seleccione primero una inmobiliaria...';
                return;
            }
            if (option.value === 'sin') {
                option.hidden = !inmo;
                option.disabled = !inmo;
                return;
            }
            const tiene = !!inmo && unidadesDe(option).some((u) => String(u.inmobiliaria_id) === String(inmo));
            option.hidden = !tiene;
            option.disabled = !tiene;
            if (tiene) visibles++;
        });

        const actual = clienteSelect.options[clienteSelect.selectedIndex];
        if (actual && actual.disabled) clienteSelect.value = '';

        if (inmo && visibles === 0) {
            clienteSelect.options[0].textContent = 'Esta inmobiliaria no tiene clientes con locales';
        }
    }

    /* ---------- Cliente -> sus locales (o inmueble si es ingreso general) ---------- */
    function cambiarCliente(seleccionado = '') {
        const sinCliente = clienteSelect.value === 'sin';

        grupoUnidad.style.display = sinCliente ? 'none' : '';
        grupoInmueble.style.display = sinCliente ? '' : 'none';
        unidadSelect.required = !sinCliente;
        inmuebleSelect.required = sinCliente;

        if (sinCliente) {
            limpiarSelect(unidadSelect, '');
            infoLocal.innerHTML = '&nbsp;';
            cargarInmuebles(inmobiliariaSelect.value, inmuebleSeleccionado);
            return;
        }

        inmuebleSelect.value = '';

        if (!clienteSelect.value) {
            limpiarSelect(unidadSelect, 'Seleccione primero un cliente...');
            mostrarInfoLocal();
            return;
        }

        const locales = localesDelCliente();
        limpiarSelect(unidadSelect, 'Seleccione un local...');

        locales.forEach((u) => {
            const option = document.createElement('option');
            option.value = u.id;
            option.textContent = `${u.nombre} — ${u.inmueble}`;
            unidadSelect.appendChild(option);
        });

        if (locales.length === 1) {
            unidadSelect.remove(0); // un solo local: se elige automáticamente
            unidadSelect.value = String(locales[0].id);
        } else if (seleccionado) {
            unidadSelect.value = String(seleccionado);
        }

        mostrarInfoLocal();
    }

    function mostrarInfoLocal() {
        const u = localesDelCliente().find((x) => String(x.id) === unidadSelect.value);
        infoLocal.innerHTML = u ? `Inmueble: <strong>${u.inmueble}</strong>` : '&nbsp;';
    }

    async function cargarInmuebles(inmobiliariaId, seleccionado = '') {
        if (!inmobiliariaId) {
            limpiarSelect(inmuebleSelect, 'Seleccione primero una inmobiliaria...');
            return;
        }

        limpiarSelect(inmuebleSelect, 'Cargando inmuebles...');

        try {
            const inmuebles = await obtenerJson(`/ajax/inmuebles/${inmobiliariaId}`);
            limpiarSelect(inmuebleSelect, 'Seleccione un inmueble...');
            inmuebles.forEach((i) => {
                const option = document.createElement('option');
                option.value = i.id;
                option.textContent = i.nombre;
                option.selected = String(i.id) === String(seleccionado);
                inmuebleSelect.appendChild(option);
            });
        } catch (error) {
            console.error(error);
            limpiarSelect(inmuebleSelect, 'No fue posible cargar los inmuebles');
        }
    }

    /* ---------- Forma de pago ---------- */
    function controlarFormaPago() {
        if (!formaPagoSelect) return;

        const texto = formaPagoSelect.options[formaPagoSelect.selectedIndex]?.text.trim().toLowerCase() || '';
        const esTransferencia = texto.includes('transferencia');

        grupoCuenta.style.display = esTransferencia ? 'block' : 'none';
        grupoReferencia.style.display = esTransferencia ? 'block' : 'none';

        if (!esTransferencia) {
            cuentaSelect.value = '';
            referenciaInput.value = '';
        }
    }

    /* ---------- Eventos ---------- */
    inmobiliariaSelect?.addEventListener('change', () => { filtrarClientes(); cambiarCliente(); });
    clienteSelect?.addEventListener('change', () => cambiarCliente());
    unidadSelect?.addEventListener('change', mostrarInfoLocal);
    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    /* ---------- Estado inicial (incluye regreso por error de validación) ---------- */
    filtrarClientes();
    cambiarCliente(unidadSeleccionada);
    controlarFormaPago();
});
