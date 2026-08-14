<div class="row">
    <div class="col-md-6 mb-3">
        <label>Nombre *</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $usuario->name ?? '') }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Correo *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $usuario->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Contraseña {{ isset($usuario) ? '(dejar vacío para no cambiar)' : '*' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label>Rol *</label>
        <select name="rol" class="form-control @error('rol') is-invalid @enderror">
            <option value="">Seleccione...</option>
            <option value="ADMIN" {{ old('rol', $usuario->rol ?? '') == 'ADMIN' ? 'selected' : '' }}>Administrador</option>
            <option value="CAPTURISTA" {{ old('rol', $usuario->rol ?? '') == 'CAPTURISTA' ? 'selected' : '' }}>Capturista</option>
        </select>
        @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>