<div class="row">

    <div class="col-md-12 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre',$tipo->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">

        <label>Descripción</label>

        <textarea
            name="descripcion"
            rows="4"
            class="form-control">{{ old('descripcion',$tipo->descripcion ?? '') }}</textarea>

    </div>

</div>