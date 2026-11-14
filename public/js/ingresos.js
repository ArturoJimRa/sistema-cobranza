document.addEventListener('DOMContentLoaded', () => {
    const inmobiliariaSelect = document.getElementById('inmobiliaria_id');
    const clienteSelect = document.getElementById('cliente_id');

    const formaPagoSelect = document.getElementById('forma_pago_id');
    const grupoCuenta = document.getElementById('grupo_cuenta_bancaria');
    const cuentaSelect = document.getElementById('cuenta_bancaria_id');
    const grupoReferencia = document.getElementById('grupo_referencia');
    const referenciaInput = document.getElementById('referencia');

    function inmobiliariasDe(option) {
        try {
            return JSON.parse(option?.dataset.inmobiliarias || '[]').map(String);
        } catch (e) {
            return [];
        }
    }

    /* ---------- Inmobiliaria -> solo clientes con locales en esa plaza ---------- */
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
            const tiene = !!inmo && inmobiliariasDe(option).includes(String(inmo));
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

    inmobiliariaSelect?.addEventListener('change', filtrarClientes);
    formaPagoSelect?.addEventListener('change', controlarFormaPago);

    filtrarClientes();
    controlarFormaPago();
});
