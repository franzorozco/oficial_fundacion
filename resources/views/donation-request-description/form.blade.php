@csrf
<div class="row p-1">
    <div class="col-md-12">
        {{-- ID de solicitud de donación (mejor un selector) --}}
        <div class="form-group mb-2">
            <label for="donation_request_id">Solicitud de Donación</label>
            <select name="donation_request_id" class="form-control @error('donation_request_id') is-invalid @enderror" id="donation_request_id">
                <option value="">Seleccione una Solicitud</option>
                @foreach($donationRequests as $request)
                    <option value="{{ $request->id }}" {{ old('donation_request_id', $donationRequestDescription?->donation_request_id) == $request->id ? 'selected' : '' }}>
                        {{ $request->title ?? 'Solicitud #' . $request->id }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('donation_request_id', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- Campos de texto --}}
        <div class="form-group mb-2">
            <label for="recipient_name">Nombre del Beneficiario</label>
            <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
                value="{{ old('recipient_name', $donationRequestDescription?->recipient_name) }}" id="recipient_name">
            {!! $errors->first('recipient_name', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="recipient_address">Dirección del Beneficiario</label>
            <input type="text" name="recipient_address" class="form-control @error('recipient_address') is-invalid @enderror"
                value="{{ old('recipient_address', $donationRequestDescription?->recipient_address) }}" id="recipient_address">
            {!! $errors->first('recipient_address', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="recipient_contact">Contacto del Beneficiario</label>
            <input type="text" name="recipient_contact" class="form-control @error('recipient_contact') is-invalid @enderror"
                value="{{ old('recipient_contact', $donationRequestDescription?->recipient_contact) }}" id="recipient_contact">
            {!! $errors->first('recipient_contact', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- Selector para tipo de beneficiario --}}
        <div class="form-group mb-2">


            <label for="tipo_beneficiario">Tipo de Beneficiario</label>
            <select name="tipo_beneficiario" class="form-control @error('tipo_beneficiario') is-invalid @enderror" id="tipo_beneficiario">
                @foreach(['individual' => 'Individual', 'organization' => 'Organización', 'community' => 'Comunidad', 'other' => 'Otro'] as $value => $label)
                    <option value="{{ $value }}" {{ old('tipo_beneficiario', $donationRequestDescription?->tipo_beneficiario) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tipo_beneficiario', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="reason">Motivo</label>
            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror"
                value="{{ old('reason', $donationRequestDescription?->reason) }}" id="reason">
            {!! $errors->first('reason', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- Mapa de Google --}}
        <div class="form-group mb-2">
            <label>Seleccionar Ubicación en el Mapa</label>
            <div id="map" style="height: 300px;"></div>
        </div>

        <div class="form-group mb-2">
            <label for="latitude">Latitud</label>
            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                value="{{ old('latitude', $donationRequestDescription?->latitude) }}" id="latitude" readonly>
            {!! $errors->first('latitude', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="longitude">Longitud</label>
            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                value="{{ old('longitude', $donationRequestDescription?->longitude) }}" id="longitude" readonly>
            {!! $errors->first('longitude', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="extra_instructions">Instrucciones Adicionales</label>
            <input type="text" name="extra_instructions" class="form-control @error('extra_instructions') is-invalid @enderror"
                value="{{ old('extra_instructions', $donationRequestDescription?->extra_instructions) }}" id="extra_instructions">
            {!! $errors->first('extra_instructions', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2">
            <label for="supporting_documents">Documentos de Respaldo (PDF)</label>

            @if(!empty($donationRequestDescription?->supporting_documents))
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ asset('storage/supporting_documents/' . $donationRequestDescription->supporting_documents) }}" 
                    class="btn btn-sm btn-outline-primary" 
                    download
                    title="Descargar documento">
                    <i class="bi bi-download"></i> {{ $donationRequestDescription->supporting_documents }}
                    </a>

                    {{-- Botón para "Eliminar" documento --}}
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemoveDoc">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>

                {{-- Campo para modificar el nombre del documento --}}
                <div class="form-group mb-2">
                    <label for="supporting_document_name">Modificar nombre del documento (sin extensión)</label>
                    @php
                        // Quitar la extensión .pdf para mostrar solo el nombre base
                        $fileBaseName = pathinfo($donationRequestDescription->supporting_documents, PATHINFO_FILENAME);
                    @endphp
                    <input type="text" 
                        name="supporting_document_name" 
                        id="supporting_document_name" 
                        class="form-control" 
                        value="{{ old('supporting_document_name', $fileBaseName) }}">
                </div>
            @endif


            {{-- Campo oculto para indicar eliminación --}}
            <input type="hidden" name="remove_supporting_document" id="remove_supporting_document" value="0">

            {{-- Campo para subir nuevo documento (editar/reemplazar) --}}
            <input type="file" name="supporting_document" accept="application/pdf" class="form-control @error('supporting_document') is-invalid @enderror">
            {!! $errors->first('supporting_document', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>


{{-- Google Maps Script --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMap" async defer></script>
<script>
    function initMap() {
        const latField = document.getElementById('latitude');
        const lngField = document.getElementById('longitude');
        const addressField = document.getElementById('recipient_address');

        const defaultLat = parseFloat(latField.value) || -16.5;
        const defaultLng = parseFloat(lngField.value) || -68.15;

        const geocoder = new google.maps.Geocoder();

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: { lat: defaultLat, lng: defaultLng },
        });

        const marker = new google.maps.Marker({
            position: { lat: defaultLat, lng: defaultLng },
            map: map,
            draggable: true,
        });

        function updateFields(lat, lng) {
            latField.value = lat.toFixed(8);
            lngField.value = lng.toFixed(8);

            geocoder.geocode({ location: { lat, lng } }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    addressField.value = results[0].formatted_address;
                } else {
                    addressField.value = '';
                    console.error('Geocoder failed due to: ' + status);
                }
            });
        }

        map.addListener('click', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();
            marker.setPosition(e.latLng);
            updateFields(lat, lng);
        });

        marker.addListener('dragend', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();
            updateFields(lat, lng);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initMap === 'function') {
            initMap();
        }
    });
    document.getElementById('btnRemoveDoc')?.addEventListener('click', function() {
        // Confirmar antes de eliminar
        if(confirm('¿Seguro que deseas eliminar el documento actual?')) {
            // Marcar para eliminar
            document.getElementById('remove_supporting_document').value = '1';

            // Opcional: ocultar la sección del documento actual
            this.closest('div.d-flex').style.display = 'none';
        }
    });
</script>
