<div class="row">

    <div class="col-md-4 mb-3">
        <label>Tipo <span class="text-danger">*</span></label>

        <select name="tipo" class="form-control @error('tipo') is-invalid @enderror">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('tipo', $concepto->tipo ?? '') == 1 ? 'selected' : '' }}>
                Ingreso
            </option>
            <option value="2" {{ old('tipo', $concepto->tipo ?? '') == 2 ? 'selected' : '' }}>
                Gasto
            </option>
        </select>

        @error('tipo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $concepto->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>