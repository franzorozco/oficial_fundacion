@extends('adminlte::page')

@section('title', __('Create Donation'))

@section('Styles')
    <style>
    .is-valid {
        border-color: #198754 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }
    </style>
@endsection

@section('content_header')
    <h1>{{ __('Registrar donación') }}</h1>
@stop

@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush


<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-0 px-4 py-4">
        <h4 class="mb-1 fw-bold">
            <i class="fas fa-hand-holding-heart text-primary me-2"></i>
            Nueva donación
        </h4>

        <p class="text-muted mb-0">
            Registra una donación completa. Completa los campos para mantener trazabilidad del proceso.
        </p>
    </div>

    <div class="card-body px-4 py-4">

        <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                {{-- ================= DONANTE EXTERNO ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-light h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-plus text-success me-1"></i>
                            Donante externo
                        </label>

                        <small class="text-muted d-block mb-2">
                            Selecciona una persona externa que realiza la donación.
                        </small>

                        <div class="input-group">
                            <select name="external_donor_id" id="external_donor_id"
                                class="form-select form-select-lg @error('external_donor_id') is-invalid @enderror">
                                <option value="">Selecciona un donante externo</option>
                                @foreach($externalDonors as $externalDonor)
                                    <option value="{{ $externalDonor->id }}"
                                        {{ old('external_donor_id', $donation?->external_donor_id) == $externalDonor->id ? 'selected' : '' }}>
                                        {{ $externalDonor->names }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button"
                                class="btn btn-success"
                                data-toggle="modal"
                                data-target="#addExternalDonorModal">
                                <i class="fas fa-plus"></i>
                                Nuevo
                            </button>
                        </div>

                        @error('external_donor_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= USUARIO RESPONSABLE ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-white h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-tie text-primary me-1"></i>
                            Usuario responsable
                        </label>

                        <small class="text-muted d-block mb-2">
                            Persona interna encargada de registrar esta donación.
                        </small>

                        <select name="user_id" id="user_id"
                            class="form-select form-select-lg @error('user_id') is-invalid @enderror">

                            <option value="">Selecciona un usuario</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $donation?->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('user_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= RECIBIDO POR ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-light h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-handshake text-warning me-1"></i>
                            Recibido por
                        </label>

                        <small class="text-muted d-block mb-2">
                            Persona que recibe físicamente la donación.
                        </small>

                        <select name="received_by_id" id="received_by_id"
                            class="form-select form-select-lg @error('received_by_id') is-invalid @enderror">

                            <option value="">Selecciona un receptor</option>

                            @foreach($receivers as $receiver)
                                <option value="{{ $receiver->id }}"
                                    {{ old('received_by_id', $donation?->received_by_id) == $receiver->id ? 'selected' : '' }}>
                                    {{ $receiver->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('received_by_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= ESTADO ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-white h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-flag text-danger me-1"></i>
                            Estado de la donación
                        </label>

                        <small class="text-muted d-block mb-2">
                            Define el estado actual del proceso.
                        </small>

                        <select name="status_id" id="status_id"
                            class="form-select form-select-lg @error('status_id') is-invalid @enderror">

                            <option value="">Selecciona el estado</option>

                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ old('status_id', $donation?->status_id ?? 2) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('status_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= CAMPAÑA ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-light h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-bullhorn text-info me-1"></i>
                            Campaña
                        </label>

                        <small class="text-muted d-block mb-2">
                            Asocia esta donación a una campaña activa.
                        </small>

                        <select name="during_campaign_id" id="during_campaign_id"
                            class="form-select form-select-lg @error('during_campaign_id') is-invalid @enderror">

                            <option value="">Selecciona la campaña</option>

                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}"
                                    {{ old('during_campaign_id', $donation?->during_campaign_id) == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('during_campaign_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= FECHA ================= --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded-4 bg-white h-100">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i>
                            Fecha de donación
                        </label>

                        <small class="text-muted d-block mb-2">
                            Registra cuándo se realizó la donación.
                        </small>

                        <div class="input-group">
                            <input type="datetime-local"
                                name="donation_date"
                                id="donation_date"
                                class="form-control @error('donation_date') is-invalid @enderror"
                                value="{{ old('donation_date', $donation?->donation_date ? date('Y-m-d\TH:i', strtotime($donation->donation_date)) : '') }}">

                            <button class="btn btn-outline-primary" type="button" onclick="setCurrentDateTime()">
                                <i class="fas fa-clock"></i> Ahora
                            </button>
                        </div>

                        @error('donation_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ================= NOTAS ================= --}}
                <div class="col-md-12">
                    <div class="p-3 border rounded-4 bg-light">

                        <label class="form-label fw-semibold">
                            <i class="fas fa-pen text-secondary me-1"></i>
                            Notas adicionales
                        </label>

                        <small class="text-muted d-block mb-2">
                            Agrega información relevante, observaciones o detalles importantes.
                        </small>

                        <textarea name="notes"
                            rows="3"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Ej: Donación entregada en buen estado, incluye caja sellada...">{{ old('notes', $donation?->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- ================= BOTÓN ================= --}}
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-5 py-2 rounded-3">
                    <i class="fas fa-arrow-right me-1"></i>
                    Siguiente
                </button>
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="addExternalDonorModal" tabindex="-1" aria-labelledby="addExternalDonorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

        <form id="addExternalDonorForm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- ================= HEADER ================= --}}
                <div class="modal-header bg-white border-0 px-4 py-3">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="addExternalDonorLabel">
                            Registrar donador externo
                        </h5>
                        <small class="text-muted">Completa la información del donante</small>
                    </div>

                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                {{-- ================= BODY ================= --}}
                <div class="modal-body px-4 py-3">

                    @csrf

                    <div class="row g-3">

                        {{-- NOMBRES --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nombre(s)</label>
                            <input type="text"
                                name="names"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Ej: Juan Carlos"
                                required>
                        </div>

                        {{-- APELLIDOS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Apellido paterno</label>
                            <input type="text"
                                name="paternal_surname"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Apellido paterno">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Apellido materno</label>
                            <input type="text"
                                name="maternal_surname"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Apellido materno">
                        </div>

                        {{-- CONTACTO --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Correo electrónico</label>
                            <input type="email"
                                name="email"
                                class="form-control form-control-lg rounded-3"
                                placeholder="correo@ejemplo.com"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text"
                                name="phone"
                                class="form-control form-control-lg rounded-3"
                                placeholder="+591 70000000">
                        </div>

                        {{-- DIRECCIÓN --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Dirección</label>
                            <input type="text"
                                name="address"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Dirección completa">
                        </div>

                        {{-- ERROR --}}
                        <div class="col-md-12">
                            <div class="text-danger small d-none" id="donorError"></div>
                        </div>

                    </div>

                </div>

                {{-- ================= FOOTER ================= --}}
                <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">

                    <button type="button"
                        class="btn btn-outline-secondary px-4"
                        data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="btn btn-success px-4">
                        Guardar donador
                    </button>

                </div>

            </div>
        </form>

    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('addExternalDonorForm');

    const inputs = form.querySelectorAll('input, select, textarea');

    const rules = {
        names: (v) => /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,}$/.test(v.trim()),
        paternal_surname: (v) => v === "" || /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,}$/.test(v.trim()),
        maternal_surname: (v) => v === "" || /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,}$/.test(v.trim()),
        email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
        phone: (v) => v === "" || /^[0-9+\s-]{6,20}$/.test(v),
        address: (v) => v.trim().length >= 5
    };

    function validateField(input) {
        const name = input.name;
        const value = input.value;

        if (!rules[name]) return true;

        const isValid = rules[name](value);

        if (isValid) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
        } else {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        }

        return isValid;
    }

    inputs.forEach(input => {

        input.addEventListener('input', () => {
            validateField(input);
        });

        input.addEventListener('blur', () => {
            validateField(input);
        });
    });

    function validateForm() {
        let valid = true;

        inputs.forEach(input => {
            const ok = validateField(input);
            if (!ok) valid = false;
        });

        return valid;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validateForm()) {
            alert("Corrige los errores antes de guardar.");
            return;
        }

        const data = new FormData(form);

        fetch("{{ route('external-donors.storeAjax') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: data
        })
        .then(async res => {
            const json = await res.json();
            if (!res.ok) throw json;
            return json;
        })
        .then(res => {

            if (res.success) {

                const select = $('#external_donor_id');

                const newOption = new Option(
                    res.donor.names,
                    res.donor.id,
                    true,
                    true
                );

                select.append(newOption);
                select.val(res.donor.id).trigger('change');

                // cerrar modal
                $('#addExternalDonorModal').modal('hide');

                setTimeout(() => {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }, 300);

                form.reset();

                inputs.forEach(i => {
                    i.classList.remove('is-valid', 'is-invalid');
                });

                document.getElementById('donorError').classList.add('d-none');
            }
        })
        .catch(err => {
            console.error(err);
        });
    });

});

function setCurrentDateTime() {
    const now = new Date();
    const formatted = now.toISOString().slice(0,16);
    document.getElementById('donation_date').value = formatted;
}

$(function () {

    function initSelect(selector, url) {
        $(selector).select2({
            placeholder: 'Buscar...',
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: params => ({ q: params.term }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name || item.names
                    }))
                }),
                cache: true
            },
            width: '100%'
        });
    }

    initSelect('#external_donor_id', '{{ route("api.external-donors") }}');
    initSelect('#user_id', '{{ route("api.users") }}');
    initSelect('#received_by_id', '{{ route("api.receivers") }}');
    initSelect('#status_id', '{{ route("api.statuses") }}');
    initSelect('#during_campaign_id', '{{ route("api.campaigns") }}');
});
</script>
@endsection