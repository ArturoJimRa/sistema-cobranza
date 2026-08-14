<div class="row">

    <div class="col-md-4 mb-3">
        <label for="fecha">
            Fecha <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            id="fecha"
            name="fecha"
            class="form-control @error('fecha') is-invalid @enderror"
            value="{{ old('fecha', now()->format('Y-m-d')) }}"
            required>

        @error('fecha')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8 mb-3">
        <label for="inmobiliaria_id">
            Inmobiliaria <span class="text-danger">*</span>
        </label>

        <select
            id="inmobiliaria_id"
            name="inmobiliaria_id"
            class="form-control @error('inmobiliaria_id') is-invalid @enderror"
            required>

            <option value="">Seleccione una inmobiliaria...</option>

            @foreach($inmobiliarias as $inmobiliaria)
                <option
                    value="{{ $inmobiliaria->id }}"
                    {{ old('inmobiliaria_id') == $inmobiliaria->id ? 'selected' : '' }}>

                    {{ $inmobiliaria->nombre }}

                </option>
            @endforeach

        </select>

        @error('inmobiliaria_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="inmueble_id">
            Inmueble <span class="text-danger">*</span>
        </label>

        <select
            id="inmueble_id"
            name="inmueble_id"
            class="form-control @error('inmueble_id') is-invalid @enderror"
            data-selected="{{ old('inmueble_id') }}"
            required>

            <option value="">Seleccione primero una inmobiliaria...</option>

        </select>

        @error('inmueble_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="concepto_id">
            Concepto del gasto <span class="text-danger">*</span>
        </label>

        <select
            id="concepto_id"
            name="concepto_id"
            class="form-control @error('concepto_id') is-invalid @enderror"
            required>

            <option value="">Seleccione un concepto...</option>

            @foreach($conceptos as $concepto)
                <option
                    value="{{ $concepto->id }}"
                    {{ old('concepto_id') == $concepto->id ? 'selected' : '' }}>

                    {{ $concepto->nombre }}

                </option>
            @endforeach

        </select>

        @error('concepto_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="importe">
            Importe <span class="text-danger">*</span>
        </label>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>

            <input
                type="number"
                id="importe"
                name="importe"
                min="0.01"
                step="0.01"
                class="form-control @error('importe') is-invalid @enderror"
                value="{{ old('importe') }}"
                placeholder="0.00"
                required>

            @error('importe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="forma_pago_id">
            Forma de pago <span class="text-danger">*</span>
        </label>

        <select
            id="forma_pago_id"
            name="forma_pago_id"
            class="form-control @error('forma_pago_id') is-invalid @enderror"
            required>

            <option value="">Seleccione una forma de pago...</option>

            @foreach($formasPago as $formaPago)
                <option
                    value="{{ $formaPago->id }}"
                    {{ old('forma_pago_id') == $formaPago->id ? 'selected' : '' }}>

                    {{ $formaPago->nombre }}

                </option>
            @endforeach

        </select>

        @error('forma_pago_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div
        class="col-md-6 mb-3"
        id="grupo_cuenta_bancaria"
        style="display:none;">

        <label for="cuenta_bancaria_id">
            Cuenta bancaria
        </label>

        <select
            id="cuenta_bancaria_id"
            name="cuenta_bancaria_id"
            class="form-control @error('cuenta_bancaria_id') is-invalid @enderror">

            <option value="">Seleccione una cuenta...</option>

            @foreach($cuentas as $cuenta)
                <option
                    value="{{ $cuenta->id }}"
                    {{ old('cuenta_bancaria_id') == $cuenta->id ? 'selected' : '' }}>

                    {{ $cuenta->banco->nombre ?? 'SIN BANCO' }}
                    -
                    {{ $cuenta->nombre }}

                </option>
            @endforeach

        </select>

        @error('cuenta_bancaria_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div
        class="col-md-6 mb-3"
        id="grupo_referencia"
        style="display:none;">

        <label for="referencia">
            Referencia bancaria
        </label>

        <input
            type="text"
            id="referencia"
            name="referencia"
            maxlength="150"
            class="form-control @error('referencia') is-invalid @enderror"
            value="{{ old('referencia') }}"
            placeholder="Folio, referencia o número de operación">

        @error('referencia')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="observaciones">
            Observaciones
        </label>

        <textarea
            id="observaciones"
            name="observaciones"
            rows="3"
            maxlength="2000"
            class="form-control @error('observaciones') is-invalid @enderror"
            placeholder="Información adicional del gasto">{{ old('observaciones') }}</textarea>

        @error('observaciones')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>