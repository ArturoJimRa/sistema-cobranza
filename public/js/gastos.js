document.addEventListener('DOMContentLoaded', () => {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const clienteSelect = document.getElementById('cliente_id');

    const observaciones = document.getElementById('observaciones');
    const obsRequerida = document.getElementById('obs_requerida');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');
    const grupoReferencia = document.getElementById('grupo_referencia');
    const referenciaInput = document.getElementById('referencia');

    function unidadesDe(option) {
        try {
            return JSON.parse(option?.dataset.unidades || '[]');
        } catch (e) {
            return [];
        }
    }

    /* ---------- Inmobiliaria -> solo clientes con locales en esa plaza ---------- */
    function filtrarClientes() {
        const inmo = inmobiliariaSelect.value;

        [...clienteSelect.options].forEach((option) => {
            if (option.value === '') return; // "Sin cliente" siempre disponible
            const tiene = !!inmo && unidadesDe(option).some((u) => String(u.inmobiliaria_id) === String(inmo));
            option.hidden = !tiene;
            option.disabled = !tiene;
        });

        const actual = clienteSelect.options[clienteSelect.selectedIndex];
        if (actual && actual.disabled) clienteSelect.value = '';
    }

    /* ---------- Con cliente: observación obligatoria ---------- */
    function cambiarCliente() {
        const conCliente = clienteSelect.value !== '';

        observaciones.required = conCliente;
        obsRequerida.style.display = conCliente ? '' : 'none';
        observaciones.placeholder = conCliente
            ? 'Explique por qué este gasto corresponde al cliente (obligatorio)'
            : 'Información adicional del gasto';
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

    inmobiliariaSelect?.addEventListener('change', () => { filtrarClientes(); cambiarCliente(); });
    clienteSelect?.addEventListener('change', cambiarCliente);
    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    filtrarClientes();
    cambiarCliente();
    controlarFormaPago();
});
