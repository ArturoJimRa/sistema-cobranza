<div class="row">

    <div class="col-md-3 mb-3">
        <label>Fecha *</label>
        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
               value="{{ old('fecha', $movimiento->fecha ?? date('Y-m-d')) }}">
        @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label>Tipo *</label>
        <select id="naturaleza" name="naturaleza" class="form-control @error('naturaleza') is-invalid @enderror">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('naturaleza', $movimiento->naturaleza ?? '') == 1 ? 'selected' : '' }}>Ingreso</option>
            <option value="2" {{ old('naturaleza', $movimiento->naturaleza ?? '') == 2 ? 'selected' : '' }}>Gasto</option>
        </select>
        @error('naturaleza') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Inmobiliaria *</label>
        <select id="inmobiliaria_id" name="inmobiliaria_id" class="form-control @error('inmobiliaria_id') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach($inmobiliarias as $inmobiliaria)
                <option value="{{ $inmobiliaria->id }}"
                    {{ old('inmobiliaria_id', $movimiento->inmobiliaria_id ?? '') == $inmobiliaria->id ? 'selected' : '' }}>
                    {{ $inmobiliaria->nombre }}
                </option>
            @endforeach
        </select>
        @error('inmobiliaria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Inmueble *</label>
        <select id="inmueble_id" name="inmueble_id" class="form-control @error('inmueble_id') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach($inmuebles as $inmueble)
                <option value="{{ $inmueble->id }}"
                    {{ old('inmueble_id', $movimiento->inmueble_id ?? '') == $inmueble->id ? 'selected' : '' }}>
                    {{ $inmueble->nombre }}
                </option>
            @endforeach
        </select>
        @error('inmueble_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Unidad *</label>
        <select id="unidad_id" name="unidad_id" class="form-control @error('unidad_id') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach($unidades as $unidad)
                <option value="{{ $unidad->id }}"
                    {{ old('unidad_id', $movimiento->unidad_id ?? '') == $unidad->id ? 'selected' : '' }}>
                    {{ $unidad->nombre }}
                </option>
            @endforeach
        </select>
        @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Concepto *</label>

        <select
            id="concepto_id"
            name="concepto_id"
            class="form-control @error('concepto_id') is-invalid @enderror">

            <option value="">Seleccione...</option>

            @foreach($conceptos as $concepto)
                <option
                    value="{{ $concepto->id }}"
                    data-tipo="{{ $concepto->tipo }}"
                    {{ old('concepto_id', $movimiento->concepto_id ?? '') == $concepto->id ? 'selected' : '' }}>

                    {{ $concepto->nombre }}
                    -
                    {{ $concepto->tipo == 1 ? 'Ingreso' : 'Gasto' }}

                </option>
            @endforeach

        </select>

        @error('concepto_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Forma de Pago *</label>
        <select id="forma_pago_id" name="forma_pago_id" class="form-control @error('forma_pago_id') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach($formasPago as $formaPago)
                <option value="{{ $formaPago->id }}"
                    {{ old('forma_pago_id', $movimiento->forma_pago_id ?? '') == $formaPago->id ? 'selected' : '' }}>
                    {{ $formaPago->nombre }}
                </option>
            @endforeach
        </select>
        @error('forma_pago_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3" id="grupo_cuenta_bancaria">
        <label>Cuenta Bancaria</label>
        <select id="cuenta_bancaria_id" name="cuenta_bancaria_id" class="form-control">
            <option value="">Sin cuenta</option>
            @foreach($cuentas as $cuenta)
                <option value="{{ $cuenta->id }}"
                    {{ old('cuenta_bancaria_id', $movimiento->cuenta_bancaria_id ?? '') == $cuenta->id ? 'selected' : '' }}>
                    {{ $cuenta->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Importe *</label>
        <input type="number" step="0.01" name="importe"
               class="form-control @error('importe') is-invalid @enderror"
               value="{{ old('importe', $movimiento->importe ?? '') }}">
        @error('importe') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8 mb-3" id="grupo_referencia">
        <label>Referencia</label>
        <input id="referencia" type="text" name="referencia" class="form-control"
               value="{{ old('referencia', $movimiento->referencia ?? '') }}">
    </div>

    <div class="col-md-12 mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" rows="3" class="form-control">{{ old('observaciones', $movimiento->observaciones ?? '') }}</textarea>
    </div>

</div>