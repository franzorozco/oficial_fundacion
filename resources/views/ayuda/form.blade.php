@extends('layouts.nav')

<style>
body {
  font-family: 'Inter', sans-serif;
  background: #f4f7fb;
}

.hero {
  height: 50vh;
  background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1604881991720-f91add269bed');
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}

.hero h1 {
  font-size: 2.5rem;
  font-weight: 700;
}

.container {
  max-width: 900px;
  margin: -80px auto 50px auto;
}

.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  padding: 2rem;
}

.label {
  font-weight: 600;
  margin-bottom: 5px;
  display: block;
}

.input, textarea, select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-bottom: 15px;
  transition: 0.3s;
}

.input:focus, textarea:focus, select:focus {
  border-color: #00ADEF;
  outline: none;
  box-shadow: 0 0 0 2px rgba(0,173,239,0.2);
}

.btn {
  width: 100%;
  background: #00ADEF;
  color: white;
  padding: 12px;
  border-radius: 8px;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

.btn:hover {
  background: #0088BE;
}

.alert {
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
}

.alert-success {
  background: #d4edda;
}

.alert-error {
  background: #f8d7da;
}

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.section-title {
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #003366;
}

.info-box {
  background: #eef6fb;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}
</style>

@section('contentprin')

<!-- HERO -->
<div class="hero">
  <div>
    <h1>Solicita Ayuda</h1>
    <p>Estamos aquí para apoyarte. Completa el formulario y nuestro equipo evaluará tu solicitud.</p>
  </div>
</div>

<!-- FORMULARIO -->
<div class="container">
  <div class="card">

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <div class="info-box">
      <strong>Importante:</strong> Proporciona información verídica. Nuestro equipo verificará cada solicitud.
    </div>
    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin:0; padding-left:15px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('ayuda.store') }}" method="POST">
      @csrf

      <div class="section-title">Información del Beneficiario</div>

      <label class="label">¿Para quién es la ayuda?</label>
      <select name="recipient_type" class="input">
        <option value="individual">Para mí</option>
        <option value="other">Otra persona</option>
        <option value="organization">Organización</option>
        <option value="community">Comunidad</option>
      </select>

      <label class="label">Nombre del beneficiario</label>
      <input type="text" name="recipient_name" 
        class="input @error('recipient_name') border-red-500 @enderror"
        value="{{ old('recipient_name') }}">
      @error('recipient_name')
      <small style="color:red">{{ $message }}</small>
      @enderror
      
      <label class="label">Contacto</label>
      <input type="text" name="recipient_contact" class="input" required>

      <label class="label">Dirección</label>
      <textarea name="recipient_address"></textarea>

      <div class="section-title">Detalles de la Solicitud</div>

      <label class="label">Motivo</label>
      <textarea name="reason" required></textarea>

      <label class="label">Notas adicionales</label>
      <textarea name="notes"></textarea>

      <label class="label">Indicaciones para voluntarios</label>
      <textarea name="extra_instructions"></textarea>

      <div class="section-title">Ubicación</div>

        <div class="section-title">Ubicación (haz clic en el mapa)</div>

        <div id="map" style="width:100%; height:400px; border-radius:10px; margin-bottom:15px;"></div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

      <br>

      <button type="submit" class="btn">
        Enviar Solicitud
      </button>

    </form>

  </div>
</div>

@endsection
<script>
function initMap() {
    const defaultLocation = { lat: -16.5000, lng: -68.1500 };

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultLocation,
    });

    let marker;

    map.addListener("click", (event) => {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;

        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
            });
        }
    });
}
</script>

<script async
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M&callback=initMap&loading=async">
</script>
<script>
document.querySelector('form').addEventListener('submit', function(e){

    const telefono = document.querySelector('[name="recipient_contact"]').value;
    const lat = document.getElementById('latitude').value;

    // Validar teléfono (solo números)
    const telefonoRegex = /^[0-9]{7,15}$/;

    if(!telefonoRegex.test(telefono)){
        alert('El contacto debe ser un número válido (solo números)');
        e.preventDefault();
        return;
    }

    // Validar mapa
    if(!lat){
        alert('Debes seleccionar una ubicación en el mapa');
        e.preventDefault();
        return;
    }

});
</script>
