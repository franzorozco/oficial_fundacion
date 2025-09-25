@php
    $users = \App\Models\User::all();
@endphp

<div class="row g-3 p-2">
    <div class="col-md-6">
        {{-- Usuario solicitante --}}
        <div class="form-group">
            <label for="user_id" class="form-label fw-semibold">Solicitante</label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                <option value="">Seleccione un usuario</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $volunteerVerification?->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{-- Usuario responsable --}}
        <div class="form-group">
            <label for="user_resp_id" class="form-label fw-semibold">Revisor asignado</label>
            <select name="user_resp_id" id="user_resp_id" class="form-control @error('user_resp_id') is-invalid @enderror">
                <option value="">Seleccione un revisor</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_resp_id', $volunteerVerification?->user_resp_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_resp_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{-- Tipo de documento --}}
        <div class="form-group">
            <label for="document_type" class="form-label fw-semibold">Tipo de documento</label>
            <input type="text" name="document_type" id="document_type" class="form-control @error('document_type') is-invalid @enderror"
                value="{{ old('document_type', $volunteerVerification?->document_type) }}" placeholder="Ej: DNI, Pasaporte, Licencia...">
            @error('document_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{-- Nombre del documento --}}
        <div class="form-group">
            <label for="name_document" class="form-label fw-semibold">Nombre del documento</label>
            <input type="text" name="name_document" id="name_document" class="form-control @error('name_document') is-invalid @enderror"
                value="{{ old('name_document', $volunteerVerification?->name_document) }}">
            @error('name_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-12">
        {{-- Documento --}}
        <div class="form-group">
            <label class="form-label fw-semibold">Adjuntar documento o incluir enlace</label>
            <div class="d-flex flex-column flex-md-row gap-2">
                <input type="file" name="document_file" class="form-control @error('document_file') is-invalid @enderror">
                <span class="align-self-center">o</span>
                <input type="url" name="document_url" class="form-control @error('document_url') is-invalid @enderror"
                    placeholder="https://ejemplo.com/documento.pdf" value="{{ old('document_url', $volunteerVerification?->document_url) }}">
            </div>
            @error('document_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            @error('document_url') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{-- Estado --}}
        <div class="form-group">
            <label for="status" class="form-label fw-semibold">Estado de la solicitud</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="pendiente" {{ old('status', $volunteerVerification?->status) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="aprobado" {{ old('status', $volunteerVerification?->status) == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                <option value="rechazado" {{ old('status', $volunteerVerification?->status) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                <option value="reconsideracion" {{ old('status', $volunteerVerification?->status) == 'reconsideracion' ? 'selected' : '' }}>En reconsideración</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-12">
        {{-- Comentario --}}
        <div class="form-group">
            <label for="coment" class="form-label fw-semibold">Observaciones o comentario del revisor</label>
            <textarea name="coment" id="coment" rows="3" class="form-control @error('coment') is-invalid @enderror">{{ old('coment', $volunteerVerification?->coment) }}</textarea>
            @error('coment') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-12 text-end mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check-circle me-1"></i> Guardar información
        </button>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.querySelector('input[name="document_file"]');
        const urlInput = document.querySelector('input[name="document_url"]');
        const nameInput = document.querySelector('input[name="name_document"]');

        // Cuando se selecciona un archivo
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;
                nameInput.value = fileName;
            }
        });

        // Cuando se pega una URL
        urlInput.addEventListener('input', function () {
            const url = urlInput.value.trim();
            if (url) {
                try {
                    const urlObject = new URL(url);
                    const pathSegments = urlObject.pathname.split('/');
                    const fileName = pathSegments[pathSegments.length - 1];
                    if (fileName) {
                        nameInput.value = fileName;
                    }
                } catch (e) {
                    // La URL no es válida, no hacer nada
                }
            }
        });
    });
</script>
