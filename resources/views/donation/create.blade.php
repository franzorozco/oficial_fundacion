@extends('adminlte::page')

@section('title', __('Create Donation'))

@section('content_header')
    <h1>{{ __('Registrar donación') }}</h1>
@stop

@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Nueva donación') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donations.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 p-3">
                    <div class="col-md-6">
                        <label for="external_donor_id" class="form-label fw-semibold">{{ __('Donante Externo') }}</label>
                        <div class="input-group">
                            <select name="external_donor_id" id="external_donor_id" class="form-select @error('external_donor_id') is-invalid @enderror">
                                <option value="">{{ __('Selecciona un donante externo') }}</option>
                                @foreach($externalDonors as $externalDonor)
                                    <option value="{{ $externalDonor->id }}" {{ old('external_donor_id', $donation?->external_donor_id) == $externalDonor->id ? 'selected' : '' }}>
                                        {{ $externalDonor->names }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#addExternalDonorModal">
                                +
                            </button>
                        </div>
                        @error('external_donor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-semibold">{{ __('Usuario Responsable') }}</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value=""  >{{ __('Selecciona un usuario') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $donation?->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="received_by_id" class="form-label fw-semibold">{{ __('Recibido por') }}</label>
                        <select name="received_by_id" id="received_by_id" class="form-select @error('received_by_id') is-invalid @enderror">
                            <option value=""  >{{ __('Selecciona un receptor') }}</option>
                            @foreach($receivers as $receiver)
                                <option value="{{ $receiver->id }}" {{ old('received_by_id', $donation?->received_by_id) == $receiver->id ? 'selected' : '' }}>
                                    {{ $receiver->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('received_by_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status_id" class="form-label fw-semibold">{{ __('Estado') }}</label>
                        <select name="status_id" id="status_id" class="form-select @error('status_id') is-invalid @enderror">
                            <option value=""  >{{ __('Selecciona el estado') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" 
                                    {{ old('status_id', $donation?->status_id ?? 2) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>

                            @endforeach
                        </select>
                        @error('status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="during_campaign_id" class="form-label fw-semibold">{{ __('Campaña') }}</label>
                        <select name="during_campaign_id" id="during_campaign_id" class="form-select @error('during_campaign_id') is-invalid @enderror">
                            <option value=""    >{{ __('Selecciona la campaña') }}</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ old('during_campaign_id', $donation?->during_campaign_id) == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('during_campaign_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha con botón para fecha/hora actual --}}
                    <div class="col-md-6">
                        <label for="donation_date" class="form-label fw-semibold">{{ __('Fecha de Donación') }}</label>
                        <div class="input-group">
                            <input type="datetime-local" name="donation_date" id="donation_date"
                                value="{{ old('donation_date', $donation?->donation_date ? date('Y-m-d\TH:i', strtotime($donation->donation_date)) : '') }}"
                                class="form-control @error('donation_date') is-invalid @enderror">
                            <button class="btn btn-outline-secondary" type="button" onclick="setCurrentDateTime()">Ahora</button>
                        </div>
                        @error('donation_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="notes" class="form-label fw-semibold">{{ __('Notas') }}</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Notas adicionales">{{ old('notes', $donation?->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mt20 mt-2">
                    <button type="submit" class="btn btn-primary">{{ __('SIGUIENTE') }}</button>
                </div>
            </form>

        </div>
    </div>


    <!-- Modal para registrar nuevo donador externo -->
<div class="modal fade" id="addExternalDonorModal" tabindex="-1" aria-labelledby="addExternalDonorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addExternalDonorForm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExternalDonorLabel">Registrar nuevo donador externo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-2">
                    <label>Nombre(s)</label>
                    <input type="text" name="names" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Apellido paterno</label>
                    <input type="text" name="paternal_surname" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Apellido materno</label>
                    <input type="text" name="maternal_surname" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Teléfono</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Dirección</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="text-danger d-none" id="donorError"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
  </div>
</div>



@stop
<script>
document.addEventListener('DOMContentLoaded', function () {
    const externalDonor = document.getElementById('external_donor_id');
    const userDonor = document.getElementById('user_id');

    function toggleDonorFields() {
        if (externalDonor.value) {
            userDonor.disabled = true;
        } else {
            userDonor.disabled = false;
        }

        if (userDonor.value) {
            externalDonor.disabled = true;
        } else {
            externalDonor.disabled = false;
        }
    }

    externalDonor.addEventListener('change', toggleDonorFields);
    userDonor.addEventListener('change', toggleDonorFields);

    toggleDonorFields();

    const form = document.getElementById('addExternalDonorForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const data = new FormData(form);
        fetch("{{ route('external-donors.storeAjax') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: data
        })

        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('external_donor_id');
                const option = document.createElement('option');
                option.value = data.donor.id;
                option.text = data.donor.names;
                option.selected = true;
                select.appendChild(option);

                // Cerrar el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addExternalDonorModal'));
                modal.hide();
                form.reset();
            } else {
                document.getElementById('donorError').textContent = data.message || 'Error al guardar.';
                document.getElementById('donorError').classList.remove('d-none');
            }
        })
        .catch(() => {
            document.getElementById('donorError').textContent = 'Error inesperado.';
            document.getElementById('donorError').classList.remove('d-none');
        });
    });

});

function setCurrentDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const formatted = `${year}-${month}-${day}T${hours}:${minutes}`;

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