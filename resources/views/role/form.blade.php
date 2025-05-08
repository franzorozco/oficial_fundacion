<!-- Formulario de creación y edición de rol -->
<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <h2 class="h3 mb-4">Permisos</h2>
        @error('permission')
            <small class="text-danger">{{ $message }}</small>
        @enderror

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

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
