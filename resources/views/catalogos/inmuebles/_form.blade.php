<div class="row">

    <div class="col-md-6 mb-3">
        <label>Inmobiliaria <span class="text-danger">*</span></label>

        <select name="inmobiliaria_id" class="form-control @error('inmobiliaria_id') is-invalid @enderror">
            <option value="">Seleccione...</option>

            @foreach($inmobiliarias as $inmobiliaria)
                <option value="{{ $inmobiliaria->id }}"
                    {{ old('inmobiliaria_id', $inmueble->inmobiliaria_id ?? '') == $inmobiliaria->id ? 'selected' : '' }}>
                    {{ $inmobiliaria->nombre }}
                </option>
            @endforeach
        </select>

        @error('inmobiliaria_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Tipo de Inmueble <span class="text-danger">*</span></label>

        <select name="tipo_inmueble_id" class="form-control @error('tipo_inmueble_id') is-invalid @enderror">
            <option value="">Seleccione...</option>

            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}"
                    {{ old('tipo_inmueble_id', $inmueble->tipo_inmueble_id ?? '') == $tipo->id ? 'selected' : '' }}>
                    {{ $tipo->nombre }}
                </option>
            @endforeach
        </select>

        @error('tipo_inmueble_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $inmueble->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label>Dirección</label>

        <textarea
            name="direccion"
            rows="3"
            class="form-control">{{ old('direccion', $inmueble->direccion ?? '') }}</textarea>
    </div>

    <div class="col-md-12 mb-3">
        <label>Observaciones</label>

        <textarea
            name="observaciones"
            rows="3"
            class="form-control">{{ old('observaciones', $inmueble->observaciones ?? '') }}</textarea>
    </div>

</div>