<div class="row">

    <div class="col-md-6 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>
        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $inmobiliaria->nombre ?? '') }}"
            required>

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Razón Social</label>
        <input
            type="text"
            name="razon_social"
            class="form-control"
            value="{{ old('razon_social', $inmobiliaria->razon_social ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>RFC</label>
        <input
            type="text"
            name="rfc"
            class="form-control"
            value="{{ old('rfc', $inmobiliaria->rfc ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Teléfono</label>
        <input
            type="text"
            name="telefono"
            class="form-control"
            value="{{ old('telefono', $inmobiliaria->telefono ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label>Correo</label>
        <input
            type="email"
            name="correo"
            class="form-control"
            value="{{ old('correo', $inmobiliaria->correo ?? '') }}">
    </div>

    <div class="col-md-12 mb-3">
        <label>Dirección</label>
        <textarea
            name="direccion"
            rows="3"
            class="form-control">{{ old('direccion', $inmobiliaria->direccion ?? '') }}</textarea>
    </div>

</div>