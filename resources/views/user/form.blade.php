<div class="row padding-1 p-1">
    {{-- Primera columna --}}
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user?->name) }}" id="name" placeholder="Nombre">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo electrónico">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña">
            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirmar contraseña">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user?->phone) }}" id="phone" placeholder="Teléfono">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="address" class="form-label">Dirección</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                value="{{ old('address', $user?->address) }}" id="address" placeholder="Dirección">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="document_number" class="form-label">Número de documento</label>
            <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                value="{{ old('document_number', $user?->profile?->document_number) }}" id="document_number" placeholder="Número de documento">
            {!! $errors->first('document_number', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="birthdate" class="form-label">Fecha de nacimiento</label>
            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                value="{{ old('birthdate', $user?->profile?->birthdate) }}" id="birthdate">
            {!! $errors->first('birthdate', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="location" class="form-label">Ubicación</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location', $user?->profile?->location) }}" id="location" placeholder="Ubicación">
            {!! $errors->first('location', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="languages_spoken" class="form-label">Idiomas hablados</label>
            <input type="text" name="languages_spoken" class="form-control @error('languages_spoken') is-invalid @enderror"
                value="{{ old('languages_spoken', $user?->profile?->languages_spoken) }}" id="languages_spoken" placeholder="Ej: Español, Inglés">
            {!! $errors->first('languages_spoken', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_days" class="form-label">Días disponibles</label>
            <input type="text" name="availability_days" class="form-control @error('availability_days') is-invalid @enderror"
                value="{{ old('availability_days', $user?->profile?->availability_days) }}" id="availability_days" placeholder="Ej: Lunes, Miércoles">
            {!! $errors->first('availability_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_hours" class="form-label">Horas disponibles</label>
            <input type="text" name="availability_hours" class="form-control @error('availability_hours') is-invalid @enderror"
                value="{{ old('availability_hours', $user?->profile?->availability_hours) }}" id="availability_hours" placeholder="Ej: 9am-5pm">
            {!! $errors->first('availability_hours', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="transport_available" class="form-label">¿Cuenta con transporte?</label>
            <select name="transport_available" id="transport_available" class="form-control @error('transport_available') is-invalid @enderror">
                <option value="0" {{ old('transport_available', $user?->profile?->transport_available) == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('transport_available', $user?->profile?->transport_available) == 1 ? 'selected' : '' }}>Sí</option>
            </select>
            {!! $errors->first('transport_available', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="experience_level" class="form-label">Nivel de experiencia</label>
            <select name="experience_level" id="experience_level" class="form-control @error('experience_level') is-invalid @enderror">
                <option value="básico" {{ old('experience_level', $user?->profile?->experience_level) == 'básico' ? 'selected' : '' }}>Básico</option>
                <option value="intermedio" {{ old('experience_level', $user?->profile?->experience_level) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                <option value="avanzado" {{ old('experience_level', $user?->profile?->experience_level) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
            </select>
            {!! $errors->first('experience_level', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="physical_condition" class="form-label">Condición física</label>
            <select name="physical_condition" id="physical_condition" class="form-control @error('physical_condition') is-invalid @enderror">
                <option value="buena" {{ old('physical_condition', $user?->profile?->physical_condition) == 'buena' ? 'selected' : '' }}>Buena</option>
                <option value="moderada" {{ old('physical_condition', $user?->profile?->physical_condition) == 'moderada' ? 'selected' : '' }}>Moderada</option>
                <option value="limitada" {{ old('physical_condition', $user?->profile?->physical_condition) == 'limitada' ? 'selected' : '' }}>Limitada</option>
            </select>
            {!! $errors->first('physical_condition', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="bio" class="form-label">Biografía</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" id="bio" rows="3" placeholder="Cuéntanos sobre ti...">{{ old('bio', $user?->profile?->bio) }}</textarea>
            {!! $errors->first('bio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
</div>
