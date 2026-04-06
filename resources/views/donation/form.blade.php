<div class="row g-4 p-3">

    <!-- ===================== DONANTE EXTERNO ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="external_donor_id" class="form-label fw-semibold">
                Donante Externo
            </label>

            <small class="text-muted d-block mb-2">
                Selecciona un donante externo si la donación proviene de una persona o institución fuera del sistema.
            </small>

            <select name="external_donor_id"
                    id="external_donor_id"
                    class="form-select form-select-lg shadow-sm @error('external_donor_id') is-invalid @enderror">

                <option value="">Selecciona un donante externo</option>

                @foreach($externalDonors as $externalDonor)
                    <option value="{{ $externalDonor->id }}"
                        {{ old('external_donor_id', $donation?->external_donor_id) == $externalDonor->id ? 'selected' : '' }}>
                        {{ $externalDonor->names }}
                    </option>
                @endforeach

            </select>

            @error('external_donor_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

        </div>
    </div>


    <!-- ===================== USUARIO RESPONSABLE ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="user_id" class="form-label fw-semibold">
                Usuario Responsable
            </label>

            <small class="text-muted d-block mb-2">
                Usuario interno encargado de registrar y gestionar la donación.
            </small>

            <select name="user_id"
                    id="user_id"
                    class="form-select form-select-lg shadow-sm @error('user_id') is-invalid @enderror">

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


    <!-- ===================== RECEPCIÓN ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="received_by_id" class="form-label fw-semibold">
                Recibido por
            </label>

            <small class="text-muted d-block mb-2">
                Persona responsable de recibir físicamente la donación.
            </small>

            <select name="received_by_id"
                    id="received_by_id"
                    class="form-select form-select-lg shadow-sm @error('received_by_id') is-invalid @enderror">

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


    <!-- ===================== ESTADO ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="status_id" class="form-label fw-semibold">
                Estado de la Donación
            </label>

            <small class="text-muted d-block mb-2">
                Define el estado actual del proceso de la donación.
            </small>

            <select name="status_id"
                    id="status_id"
                    class="form-select form-select-lg shadow-sm @error('status_id') is-invalid @enderror">

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


    <!-- ===================== CAMPAÑA ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="during_campaign_id" class="form-label fw-semibold">
                Campaña Asociada
            </label>

            <small class="text-muted d-block mb-2">
                Selecciona la campaña vinculada a esta donación (si aplica).
            </small>

            <select name="during_campaign_id"
                    id="during_campaign_id"
                    class="form-select form-select-lg shadow-sm @error('during_campaign_id') is-invalid @enderror">

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


    <!-- ===================== FECHA ===================== -->
    <div class="col-md-6">
        <div class="p-3 border rounded-3 bg-light h-100">

            <label for="donation_date" class="form-label fw-semibold">
                Fecha de Donación
            </label>

            <small class="text-muted d-block mb-2">
                Registra la fecha y hora en que se realizó la donación.
            </small>

            <div class="input-group input-group-lg shadow-sm">

                <input type="datetime-local"
                       name="donation_date"
                       id="donation_date"
                       value="{{ old('donation_date', $donation?->donation_date ? date('Y-m-d\TH:i', strtotime($donation->donation_date)) : '') }}"
                       class="form-control @error('donation_date') is-invalid @enderror">

                <button class="btn btn-outline-secondary" type="button" onclick="setCurrentDateTime()">
                    Ahora
                </button>

            </div>

            @error('donation_date')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

        </div>
    </div>


    <!-- ===================== NOTAS ===================== -->
    <div class="col-md-12">
        <div class="p-3 border rounded-3 bg-light">

            <label for="notes" class="form-label fw-semibold">
                Notas adicionales
            </label>

            <small class="text-muted d-block mb-2">
                Información complementaria, observaciones o detalles relevantes de la donación.
            </small>

            <textarea name="notes"
                      id="notes"
                      rows="4"
                      class="form-control shadow-sm @error('notes') is-invalid @enderror"
                      placeholder="Escribe aquí cualquier observación importante...">{{ old('notes', $donation?->notes) }}</textarea>

            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

        </div>
    </div>

</div>



    
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
</script>
