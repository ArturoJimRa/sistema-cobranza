<div class="row">

    <div class="col-md-8 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $formaPago->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label>Clave Microsip</label>

        <input
            type="text"
            name="clave_microsip"
            maxlength="20"
            class="form-control @error('clave_microsip') is-invalid @enderror"
            value="{{ old('clave_microsip', $formaPago->clave_microsip ?? '') }}">

        <small class="form-text text-muted">Opcional. Solo informativa por ahora.</small>

        @error('clave_microsip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
