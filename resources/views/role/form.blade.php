<!-- Formulario de creación y edición de rol -->
<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <!-- Campo para el nombre del rol -->
        <div class="form-group mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Título de la sección de permisos -->
        <h2 class="h3 mb-4">Permisos</h2>
        @error('permission')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        <!-- Sección de permisos -->
        <div class="row permissions-row">
            @foreach($permissions as $permission)
                <div class="col-sm-6 col-md-4 mb-3">
                    <div class="custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                            @if(in_array($permission->id, $rolePermissions)) checked @endif>
                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                            {{ $permission->description }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Botón de envío -->
    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
