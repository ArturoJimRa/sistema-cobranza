<div class="row">

    <div class="col-md-12 mb-3">
        <label>Inmueble <span class="text-danger">*</span></label>

        <select name="inmueble_id" class="form-control @error('inmueble_id') is-invalid @enderror">
            <option value="">Seleccione...</option>

            @foreach($inmuebles as $inmueble)
                <option value="{{ $inmueble->id }}"
                    {{ old('inmueble_id', $unidad->inmueble_id ?? '') == $inmueble->id ? 'selected' : '' }}>
                    {{ $inmueble->nombre }}
                </option>
            @endforeach
        </select>

        @error('inmueble_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Nombre <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $unidad->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Razón Social</label>

        <input
            type="text"
            name="razon_social"
            class="form-control @error('razon_social') is-invalid @enderror"
            value="{{ old('razon_social', $unidad->razon_social ?? '') }}">

        @error('razon_social')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>