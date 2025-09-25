<style>
  #map {
    height: 400px;
    width: 100%;
    cursor: crosshair;
    user-select: none;
  }
</style>

<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="event_id" class="form-label">{{ __('ID del Evento') }}</label>
            <select name="event_id" class="form-control @error('event_id') is-invalid @enderror" id="event_id">
                <option value="">{{ __('Seleccione un evento') }}</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" {{ old('event_id', $eventLocation?->event_id) == $event->id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('event_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <div class="form-group mb-2 mb20">
            <label for="location_name" class="form-label">{{ __('Nombre del Lugar') }}</label>
            <input type="text" name="location_name" class="form-control @error('location_name') is-invalid @enderror" value="{{ old('location_name', $eventLocation?->location_name) }}" id="location_name" placeholder="Nombre del Lugar">
            {!! $errors->first('location_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

            <div class="form-group mb-2 mb20">
            <label for="address" class="form-label">{{ __('Dirección') }}</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $eventLocation?->address) }}" id="address" placeholder="Dirección">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

            <div class="form-group mb-2 mb20">
            <label for="latitud" class="form-label">{{ __('Latitud') }}</label>
            <input type="text" name="latitud" class="form-control @error('latitud') is-invalid @enderror" value="{{ old('latitud', $eventLocation?->latitud) }}" id="latitud" placeholder="Latitud">
            {!! $errors->first('latitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

            <div class="form-group mb-2 mb20">
            <label for="longitud" class="form-label">{{ __('Longitud') }}</label>
            <input type="text" name="longitud" class="form-control @error('longitud') is-invalid @enderror" value="{{ old('longitud', $eventLocation?->longitud) }}" id="longitud" placeholder="Longitud">
            {!! $errors->first('longitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        <div class="form-group mb-2 mb20">
            <label for="start_hour" class="form-label">{{ __('Hora de Inicio') }}</label>
            <input type="time" name="start_hour" class="form-control @error('start_hour') is-invalid @enderror" value="{{ old('start_hour', $eventLocation?->start_hour) }}" id="start_hour">
            {!! $errors->first('start_hour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="end_hour" class="form-label">{{ __('Hora de Finalización') }}</label>
            <input type="time" name="end_hour" class="form-control @error('end_hour') is-invalid @enderror" value="{{ old('end_hour', $eventLocation?->end_hour) }}" id="end_hour">
            {!! $errors->first('end_hour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
   <div id="map"></div>

    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
    </div>
</div>
<!-- Agrega esto antes de cerrar el body -->
<script>
  let map;
  let marker;
  let geocoder;

  function initMap() {
  geocoder = new google.maps.Geocoder();

  const latInput = document.getElementById("latitud");
  const lngInput = document.getElementById("longitud");

  const defaultLat = parseFloat(latInput.value);
  const defaultLng = parseFloat(lngInput.value);

  const defaultLocation = {
    lat: !isNaN(defaultLat) ? defaultLat : 19.432608,
    lng: !isNaN(defaultLng) ? defaultLng : -99.133209,
  };

  map = new google.maps.Map(document.getElementById("map"), {
    center: defaultLocation,
    zoom: 14,
  });

  marker = new google.maps.Marker({
    position: defaultLocation,
    map: (!isNaN(defaultLat) && !isNaN(defaultLng)) ? map : null,
    draggable: true,
  });

  // Solo actualizar inputs si ya hay una ubicación válida cargada
  if (!isNaN(defaultLat) && !isNaN(defaultLng)) {
    updateLocationInputs(defaultLocation);
  }

  // Click en el mapa para poner/mover marcador
  map.addListener("click", (e) => {
    if (!e.latLng) return;
    marker.setPosition(e.latLng);
    if (!marker.getMap()) marker.setMap(map);
    updateLocationInputs(e.latLng);
  });

  // Arrastrar marcador
  marker.addListener("dragend", (e) => {
    updateLocationInputs(e.latLng);
  });
}

function updateLocationInputs(latLng) {
  let lat, lng;

  if (typeof latLng.lat === "function" && typeof latLng.lng === "function") {
    lat = latLng.lat();
    lng = latLng.lng();
  } else if (typeof latLng.lat === "number" && typeof latLng.lng === "number") {
    lat = latLng.lat;
    lng = latLng.lng;
  } else {
    console.warn("Ubicación inválida:", latLng);
    return;
  }

  document.getElementById("latitud").value = lat.toFixed(8);
  document.getElementById("longitud").value = lng.toFixed(8);

  geocoder.geocode({ location: { lat: lat, lng: lng } }, (results, status) => {
    if (status === "OK" && results[0]) {
      const result = results[0];
      document.getElementById("address").value = result.formatted_address;

      // Solo actualizar nombre del lugar si está vacío para no sobreescribir
      const locationNameInput = document.getElementById("location_name");
      if (!locationNameInput.value.trim()) {
        let placeName = "";
        for (const component of result.address_components) {
          if (component.types.includes("establishment") || component.types.includes("point_of_interest")) {
            placeName = component.long_name;
            break;
          }
        }

        if (!placeName) {
          placeName = result.formatted_address.split(",")[0];
        }

        locationNameInput.value = placeName;
      }
    } else {
      console.warn("Geocodificación fallida: " + status);
    }
  });
}


  window.initMap = initMap;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMap" async defer></script>