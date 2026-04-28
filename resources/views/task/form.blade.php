<div class="row padding-1 p-1">
    <div class="col-md-12">

        {{-- CREADOR --}}
        <input type="hidden" name="creator_id" value="{{ auth()->id() }}">

        {{-- NOMBRE --}}
        <div class="form-group mb-2">
            <label>Nombre</label>
            <input type="text" name="name" maxlength="100"
                class="form-control"
                value="{{ old('name', $task?->name) }}"
                required>
            <small class="text-danger" id="error-name"></small>
        </div>

        {{-- DESCRIPCIÓN --}}
        <div class="form-group mb-2">
            <label>Descripción</label>
            <textarea name="description" maxlength="255"
                class="form-control"
                required>{{ old('description', $task?->description) }}</textarea>
            <small class="text-danger" id="error-description"></small>
        </div>

        {{-- DÍAS --}}
        <div class="form-group">
            <label>Días requeridos</label>
            <select name="required_days[]" multiple class="form-control">
                @php
                    $days = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
                    $selectedDays = old('required_days', explode(',', $task?->required_days ?? ''));
                @endphp

                @foreach($days as $day)
                    <option value="{{ $day }}"
                        {{ in_array($day, $selectedDays) ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- HORAS --}}
        @php
            $start = null;
            $end = null;

            if($task && $task->required_hours){
                [$start, $end] = explode(' - ', $task->required_hours);
            }
        @endphp

        <div class="row mt-2">
            <div class="col-md-6">
                <label>Hora inicio</label>
                <input type="time" name="start_hour" class="form-control"
                    value="{{ old('start_hour', $start) }}" required>
            </div>

            <div class="col-md-6">
                <label>Hora fin</label>
                <input type="time" name="end_hour" class="form-control"
                    value="{{ old('end_hour', $end) }}" required>
            </div>
        </div>
        <small class="text-danger" id="error-hours"></small>

        {{-- UBICACIÓN --}}
        <div class="form-group mt-2">
            <label>Ubicación</label>
            <input type="text" id="autocomplete" name="location"
                class="form-control"
                value="{{ old('location', $task?->location) }}">
        </div>

        {{-- COORDENADAS --}}
        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $task?->latitude) }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $task?->longitude) }}">

        {{-- MAPA --}}
        <div class="form-group mt-2">
            <label>Seleccionar ubicación en el mapa</label>
            <div id="map" style="height: 400px; border-radius:10px;"></div>
        </div>
        {{-- TRANSPORTE --}}
        <div class="form-check mt-2">
            <input type="checkbox" name="requires_transport" value="1"
                class="form-check-input"
                {{ old('requires_transport', $task?->requires_transport) ? 'checked' : '' }}>
            <label class="form-check-label">Requiere transporte</label>
        </div>

        <hr>

        {{-- SKILLS --}}
        <h5>Habilidades requeridas (máx 5)</h5>

        @php
            $taskSkills = old('skills', $task?->requirements->pluck('skill_id')->toArray() ?? []);
            $taskLevels = old('levels', $task?->requirements->pluck('required_level')->toArray() ?? []);
        @endphp

        <div id="skills-container">

            @forelse($taskSkills as $index => $skillId)
                <div class="row mb-2 skill-row">
                    <div class="col-md-6">
                        <select name="skills[]" class="form-control" required>
                            <option value="">Seleccionar</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}"
                                    {{ $skill->id == $skillId ? 'selected' : '' }}>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="levels[]" class="form-control">
                            <option value="básico" {{ ($taskLevels[$index] ?? '') == 'básico' ? 'selected' : '' }}>Básico</option>
                            <option value="intermedio" {{ ($taskLevels[$index] ?? '') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ ($taskLevels[$index] ?? '') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-skill">X</button>
                    </div>
                </div>
            @empty
                <div class="row mb-2 skill-row">
                    <div class="col-md-6">
                        <select name="skills[]" class="form-control" required>
                            <option value="">Seleccionar</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="levels[]" class="form-control">
                            <option value="básico">Básico</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-skill">X</button>
                    </div>
                </div>
            @endforelse

        </div>

        <button type="button" id="add-skill" class="btn btn-secondary mt-2">
            + Agregar habilidad
        </button>

    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</div>

<script>
// =============================
// VALIDACIONES EN TIEMPO REAL
// =============================

const nameInput = document.querySelector('[name="name"]');
const descInput = document.querySelector('[name="description"]');
const startInput = document.querySelector('[name="start_hour"]');
const endInput = document.querySelector('[name="end_hour"]');
const form = document.querySelector('form');

nameInput.addEventListener('input', function () {
    document.getElementById('error-name').textContent =
        this.value.length < 3 ? "Mínimo 3 caracteres" : "";
});

descInput.addEventListener('input', function () {
    document.getElementById('error-description').textContent =
        this.value.length < 10 ? "Mínimo 10 caracteres" : "";
});

function validateHours() {
    let error = document.getElementById('error-hours');
    error.textContent =
        (startInput.value && endInput.value && startInput.value >= endInput.value)
        ? "La hora fin debe ser mayor"
        : "";
}

startInput.addEventListener('change', validateHours);
endInput.addEventListener('change', validateHours);


// =============================
// VALIDACIÓN AL ENVIAR
// =============================

form.addEventListener('submit', function (e) {

    let isValid = true;

    // limpiar errores previos
    document.getElementById('error-name').textContent = "";
    document.getElementById('error-description').textContent = "";
    document.getElementById('error-hours').textContent = "";

    // ===== NOMBRE =====
    if (nameInput.value.trim().length < 3) {
        document.getElementById('error-name').textContent = "El nombre debe tener al menos 3 caracteres";
        isValid = false;
    }

    // ===== DESCRIPCIÓN =====
    if (descInput.value.trim().length < 10) {
        document.getElementById('error-description').textContent = "La descripción debe tener al menos 10 caracteres";
        isValid = false;
    }

    // ===== HORAS =====
    if (!startInput.value || !endInput.value) {
        document.getElementById('error-hours').textContent = "Debe ingresar ambas horas";
        isValid = false;
    } else if (startInput.value >= endInput.value) {
        document.getElementById('error-hours').textContent = "La hora fin debe ser mayor";
        isValid = false;
    }

    // ===== DÍAS =====
    let daysSelected = document.querySelector('[name="required_days[]"]').selectedOptions;
    if (daysSelected.length === 0) {
        alert("Debe seleccionar al menos un día");
        isValid = false;
    }

    // ===== SKILLS =====
    let skillSelects = document.querySelectorAll('[name="skills[]"]');
    let skillError = false;

    skillSelects.forEach(select => {
        if (!select.value) {
            select.classList.add('is-invalid');
            skillError = true;
        } else {
            select.classList.remove('is-invalid');
        }
    });

    if (skillError) {
        alert("Debe seleccionar todas las habilidades");
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }

});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&libraries=places"></script>
<script>
let map;
let marker;
let autocomplete;

document.addEventListener("DOMContentLoaded", function () {

    const defaultLat = -16.5000;
    const defaultLng = -68.1500;

    let lat = parseFloat(document.getElementById('latitude').value);
    let lng = parseFloat(document.getElementById('longitude').value);

    if (isNaN(lat) || isNaN(lng)) {
        lat = defaultLat;
        lng = defaultLng;
    }

    const position = { lat: lat, lng: lng };

    // 🗺️ MAPA
    map = new google.maps.Map(document.getElementById("map"), {
        center: position,
        zoom: 15,
        gestureHandling: "greedy" // 🔥 IMPORTANTE (arregla el click)
    });

    // 📍 MARCADOR
    marker = new google.maps.Marker({
        position: position,
        map: map,
        draggable: true
    });

    // 🖱️ CLICK EN MAPA (AQUÍ ESTABA TU PROBLEMA)
    map.addListener("click", function (e) {
        marker.setPosition(e.latLng);
        updateInputs(e.latLng);
    });

    // 🔄 DRAG
    marker.addListener("dragend", function (e) {
        updateInputs(e.latLng);
    });

    // 🔍 AUTOCOMPLETE
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("autocomplete")
    );

    autocomplete.addListener("place_changed", function () {
        let place = autocomplete.getPlace();

        if (!place.geometry) return;

        let location = place.geometry.location;

        map.setCenter(location);
        marker.setPosition(location);

        updateInputs(location);
    });

});

// 📍 actualizar inputs
function updateInputs(location) {
    document.getElementById('latitude').value = location.lat();
    document.getElementById('longitude').value = location.lng();
}
</script>