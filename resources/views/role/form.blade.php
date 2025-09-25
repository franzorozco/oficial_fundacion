<!-- Formulario de creación y edición de rol -->
<div class="row padding-1 p-1">
    <div class="col-md-12">

        <!-- Filtro por grupo -->
        <div class="mb-3">
            <label for="groupFilter" class="form-label">Filtrar por grupo:</label>
            <select id="groupFilter" class="form-select" onchange="filterPermissions(this.value)">
                <option value="">Todos</option>
                @foreach($groupedPermissions as $group => $permissions)
                    <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botón seleccionar todos -->
        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPermissions(true)">Seleccionar todos</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllPermissions(false)">Deseleccionar todos</button>
            <button type="submit" class="btn btn-sm btn-outline-success">{{ __('Submit') }}</button>
        </div>
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

        <!-- Lista de permisos -->
        <div class="row permissions-row">
            @foreach($groupedPermissions as $group => $permissions)
                @foreach($permissions as $permission)
                    <div class="col-sm-6 col-md-4 mb-2 permission-item" data-group="{{ $group }}">
                        <div class="form-check">
                            <input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                class="form-check-input"
                                id="perm_{{ $permission->id }}"
                                @if(in_array($permission->id, $rolePermissions)) checked @endif>
                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
<script>
function filterPermissions(group) {
    document.querySelectorAll('.permission-item').forEach(el => {
        el.style.display = !group || el.dataset.group === group ? 'block' : 'none';
    });
}

function toggleAllPermissions(checked) {
    document.querySelectorAll('input[name="permission[]"]').forEach(el => {
        el.checked = checked;
    });
}
</script>
