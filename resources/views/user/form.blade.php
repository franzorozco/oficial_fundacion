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

    <!-- Idiomas hablados -->
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="languages_spoken" class="form-label">Idiomas hablados</label>
            <input type="text" name="languages_spoken" class="form-control"
                id="languages_spoken"
                value="{{ old('languages_spoken', $user?->profile?->languages_spoken) }}"
                readonly>
            <div class="mt-2" id="language-options" class="d-flex flex-wrap gap-1">
                @php
                    $idiomas = ['Español', 'Inglés', 'Francés', 'Portugués', 'Quechua', 'Aymara'];
                @endphp
                @foreach($idiomas as $idioma)
                    <button type="button" class="btn btn-outline-dark btn-sm language-btn">{{ $idioma }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Días disponibles -->
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_days" class="form-label">Días disponibles</label>
            <input type="text" name="availability_days" class="form-control"
                id="availability_days"
                value="{{ old('availability_days', $user?->profile?->availability_days) }}"
                readonly>
            <div class="mt-2" id="days-options" class="d-flex flex-wrap gap-1">
                @php
                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                @endphp
                @foreach($dias as $dia)
                    <button type="button" class="btn btn-outline-dark btn-sm day-btn">{{ $dia }}</button>
                @endforeach
            </div>
        </div>
    </div>


        <!-- Horas disponibles -->
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_hours" class="form-label">Horas disponibles</label>
            <input type="text" name="availability_hours" class="form-control"
                id="availability_hours"
                value="{{ old('availability_hours', $user?->profile?->availability_hours) }}"
                readonly>
            <div class="mt-2" id="hours-options" class="d-flex flex-wrap gap-1">
                @php
                    $horas = ['Mañana', 'Medio día', 'Tarde', 'Noche', 'Todo el día'];
                @endphp
                @foreach($horas as $hora)
                    <button type="button" class="btn btn-outline-dark btn-sm hour-btn">{{ $hora }}</button>
                @endforeach
            </div>
        </div>
    </div>


        <!-- Tipos de transporte -->
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="transport_available" class="form-label">Tipos de transporte</label>
            <input type="text" name="transport_available" class="form-control"
                id="transport_available"
                value="{{ old('transport_available', $user?->profile?->transport_available) }}"
                readonly>
            <div class="mt-2" id="transport-options" class="d-flex flex-wrap gap-1">
                @php
                    $transportes = ['A pie', 'Bicicleta', 'Moto', 'Auto', 'Camioneta', 'Transporte público'];
                @endphp
                @foreach($transportes as $transporte)
                    <button type="button" class="btn btn-outline-dark btn-sm transport-btn">{{ $transporte }}</button>
                @endforeach
            </div>
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

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="latitude" class="form-label">Latitud</label>
            <input type="text" name="latitude" id="latitude" class="form-control" readonly
       value="{{ old('latitude', $user?->profile?->latitude) }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="longitude" class="form-label">Longitud</label>
           <input type="text" name="longitude" id="longitude" class="form-control" readonly
       value="{{ old('longitude', $user?->profile?->longitude) }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="map" class="form-label">Selecciona tu ubicación en el mapa</label>
            <div id="map" style="width: 100%; height: 400px; border-radius: 10px;"></div>
        </div>
    </div>



</div> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMap" async defer></script>
<script>

document.addEventListener('DOMContentLoaded', () => {

    function setupSelection(inputId, buttonClass) {
    const input = document.getElementById(inputId);
    const buttons = document.querySelectorAll(`.${buttonClass}`);

    let selected = [];

    if (input.value && input.value.trim() !== '') {
        selected = input.value
            .split(',')
            .map(v => v.trim().toLowerCase());
    }

    buttons.forEach(button => {
        const value = button.textContent.trim();
        const normalizedValue = value.toLowerCase();

        if (selected.includes(normalizedValue)) {
            button.classList.add('btn-dark');
            button.classList.remove('btn-outline-dark');
        }

        button.addEventListener('click', () => {
            const index = selected.indexOf(normalizedValue);

            if (index === -1) {
                selected.push(normalizedValue);
                button.classList.add('btn-dark');
                button.classList.remove('btn-outline-dark');
            } else {
                selected.splice(index, 1);
                button.classList.remove('btn-dark');
                button.classList.add('btn-outline-dark');
            }

            input.value = selected
                .map(val => val.charAt(0).toUpperCase() + val.slice(1))
                .join(', ');
        });
    });
}

    setupSelection('languages_spoken', 'language-btn');
    setupSelection('availability_days', 'day-btn');
    setupSelection('availability_hours', 'hour-btn');
    setupSelection('transport_available', 'transport-btn');

});

document.addEventListener('DOMContentLoaded', () => {
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const locationInput = document.getElementById('location');
    const addressInput = document.getElementById('address');

    let map, marker, geocoder;

window.initMap = function () {
    geocoder = new google.maps.Geocoder();

    const latInput = document.getElementById('latitude').value;
    const lngInput = document.getElementById('longitude').value;

    const lat = parseFloat(latInput);
    const lng = parseFloat(lngInput);

    const initialLat = !isNaN(lat) ? lat : -16.5;
    const initialLng = !isNaN(lng) ? lng : -68.15;

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: initialLat, lng: initialLng },
    });

    marker = new google.maps.Marker({
        position: { lat: initialLat, lng: initialLng },
        map: map,
        draggable: true
    });

    marker.addListener('dragend', function () {
        const position = marker.getPosition();
        
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();
        updateLocation(position.lat(), position.lng()); // 🔥 AQUI
    });

    map.addListener('click', function (event) {
        marker.setPosition(event.latLng);

        document.getElementById('latitude').value = event.latLng.lat();
        document.getElementById('longitude').value = event.latLng.lng();
        updateLocation(event.latLng.lat(), event.latLng.lng()); // 🔥 AQUI
    });
    
};


    function updateLocation(lat, lng) {
        latitudeInput.value = lat.toFixed(6);
        longitudeInput.value = lng.toFixed(6);

        const latlng = { lat: lat, lng: lng };
        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === 'OK') {
                if (results[0]) {
                    const addressComponents = results[0].address_components;
                    let country = '';
                    let department = '';
                    let street = '';

                    addressComponents.forEach(component => {
                        const types = component.types;

                        if (types.includes('country')) {
                            country = component.long_name;
                        }
                        if (types.includes('administrative_area_level_1')) {
                            department = component.long_name;
                        }
                        if (types.includes('route') || types.includes('street_address')) {
                            street = component.long_name;
                        }
                    });

                    locationInput.value = `${country}${department ? ', ' + department : ''}`;

                    addressInput.value = results[0].formatted_address;

                } else {
                    locationInput.value = '';
                    addressInput.value = '';
                }
            } else {
                console.error('Geocoder failed due to: ' + status);
            }
        });
    }

    initMap();
});

</script>
