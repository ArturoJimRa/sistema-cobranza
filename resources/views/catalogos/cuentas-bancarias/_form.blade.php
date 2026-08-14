<div class="row">

    <div class="col-md-6 mb-3">
        <label>Banco <span class="text-danger">*</span></label>

        <select name="banco_id" class="form-control @error('banco_id') is-invalid @enderror">
            <option value="">Seleccione...</option>

            @foreach($bancos as $banco)
                <option value="{{ $banco->id }}"
                    {{ old('banco_id', $cuenta->banco_id ?? '') == $banco->id ? 'selected' : '' }}>
                    {{ $banco->nombre }}
                </option>
            @endforeach
        </select>

        @error('banco_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Nombre de la cuenta <span class="text-danger">*</span></label>

        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $cuenta->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Número de cuenta</label>

        <input
            type="text"
            name="numero_cuenta"
            class="form-control"
            value="{{ old('numero_cuenta', $cuenta->numero_cuenta ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>CLABE</label>

        <input
            type="text"
            name="clabe"
            maxlength="18"
            class="form-control"
            value="{{ old('clabe', $cuenta->clabe ?? '') }}">
    </div>

</div>