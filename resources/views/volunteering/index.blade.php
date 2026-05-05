  @extends('layouts.nav')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    font-family: 'Inter', sans-serif;
    background: #f4f7fb;
  }
  
  /* HERO */
  .hero {
    height: 40vh;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                url('https://images.unsplash.com/photo-1593113598332-cd288d649433');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
  }

  .hero h1 {
    font-size: 2.3rem;
    font-weight: 700;
  }

  /* CONTENEDOR */
  .container-custom {
    max-width: 1200px;
    margin: -60px auto 50px auto;
  }

  /* GRID */
  .grid-tasks {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
  }

  /* TARJETA */
  .task-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 20px;
    transition: 0.3s;
  }

  .task-card:hover {
    transform: translateY(-5px);
  }

  /* BADGES */
  .badge-skill {
    background: #e3f2fd;
    color: #0077b6;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    margin-right: 5px;
  }

  /* BOTONES */
  .btn-primary {
    background: #00ADEF;
    border: none;
  }

  .btn-primary:hover {
    background: #0088BE;
  }

  .btn-success {
    background: #28a745;
    border: none;
  }

  /* BOTONES BASE */
  .btn {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  /* POSTULAR */
  .btn-primary {
    background: #00ADEF;
    color: white;
  }

  .btn-primary:hover {
    background: #0088BE;
    transform: translateY(-1px);
  }

  /* VER DETALLE */
  .btn-outline-primary {
    background: transparent;
    border: 2px solid #00ADEF;
    color: #00ADEF;
  }

  .btn-outline-primary:hover {
    background: #00ADEF;
    color: white;
    transform: translateY(-1px);
  }

  /* YA POSTULADO */
  .btn-success {
    background: #28a745;
    color: white;
    opacity: 0.8;
    cursor: not-allowed;
  }

  /* ESPACIADO */
  .btn + .btn {
    margin-top: 10px;
  }

  .modal-content {
    border-radius: 12px;
  }

  .modal-header {
    background: #00ADEF;
    color: white;
  }

  .modal-title {
    font-weight: 600;
  }
  </style>

  @section('contentprin')

  <!-- HERO -->
  <div class="hero">
    <div>
      <h1>Oportunidades de Voluntariado</h1>
      <p>Explora tareas disponibles y forma parte del cambio</p>
    </div>
  </div>

  <!-- CONTENIDO -->
  <div class="container-custom">

      {{-- MENSAJES --}}
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

      <div class="grid-tasks">

          @foreach($tasks as $task)
          <div class="task-card">

              <h4><strong>{{ $task->name }}</strong></h4>

              <p>{{ Str::limit($task->description, 100) }}</p>

              <p><strong>📍</strong> {{ $task->location ?? 'No especificada' }}</p>
              <p><strong>🕒</strong> {{ $task->required_hours }}</p>
              <p><strong>📅</strong> {{ $task->required_days }}</p>
              <p><strong>🚗</strong> {{ $task->requires_transport ? 'Requiere transporte' : 'Sin transporte' }}</p>

              {{-- SKILLS --}}
              <div class="mb-2">
                  @foreach($task->skills as $skill)
                      <span class="badge-skill">{{ $skill->name }}</span>
                  @endforeach
              </div>
@php
    $assignment = $task->taskAssignments->first();
@endphp

{{-- BOTÓN VER DETALLE (SIEMPRE visible) --}}
<button 
    class="btn btn-outline-primary open-modal"
    data-name="{{ $task->name }}"
    data-description="{{ $task->description }}"
    data-location="{{ $task->location }}"
    data-hours="{{ $task->required_hours }}"
    data-days="{{ $task->required_days }}"
    data-transport="{{ $task->requires_transport ? 'Sí' : 'No' }}"
    data-lat="{{ $task->latitude }}"
    data-lng="{{ $task->longitude }}"
>
    Ver detalle
</button>

{{-- ESTADO --}}
@if($assignment)
    <p class="mt-2">
        <strong>Estado:</strong>
        <span class="badge 
            @if($assignment->status == 'solicitada') badge-warning
            @elseif($assignment->status == 'asignada') badge-info
            @elseif($assignment->status == 'en_progreso') badge-primary
            @elseif($assignment->status == 'completada') badge-success
            @elseif($assignment->status == 'denegada') badge-danger
            @elseif($assignment->status == 'cancelada') badge-secondary
            @endif
        ">
            {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
        </span>
    </p>

    <button class="btn btn-success">
        ✔ Ya estás postulado
    </button>

@else

    <form method="POST" action="{{ route('volunteering.apply', $task->id) }}">
        @csrf
        <button class="btn btn-primary mt-2">
            Postularme
        </button>
    </form>

@endif

          </div>
          @endforeach

      </div>
  </div>

  <!-- MODAL -->
  <!-- MODAL -->
  <div class="modal fade" id="taskModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">

              <div class="modal-header">
                  <h5 class="modal-title" id="modal-title"></h5>
                  <button type="button" class="close" data-dismiss="modal">
                      <span>&times;</span>
                  </button>
              </div>

              <div class="modal-body">
                  <p id="modal-description"></p>

                  <p><strong>📍</strong> <span id="modal-location"></span></p>
                  <p><strong>🕒</strong> <span id="modal-hours"></span></p>
                  <p><strong>📅</strong> <span id="modal-days"></span></p>
                  <p><strong>🚗</strong> <span id="modal-transport"></span></p>

                  <div id="modal-map" style="height: 400px;"></div>
              </div>

          </div>
      </div>
  </div>

  @endsection

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GCanK5Gxm26zDyPrKc7MNy7WhAJZK7M"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  <script>
  let modalMap = null;
  let modalMarker = null;

  $(document).on('click', '.open-modal', function () {

      let btn = $(this);

      $('#modal-title').text(btn.data('name'));
      $('#modal-description').text(btn.data('description'));
      $('#modal-location').text(btn.data('location') || 'No especificada');
      $('#modal-hours').text(btn.data('hours'));
      $('#modal-days').text(btn.data('days'));
      $('#modal-transport').text(btn.data('transport'));

      $('#taskModal').modal('show');

      setTimeout(() => {

          let lat = parseFloat(btn.data('lat'));
          let lng = parseFloat(btn.data('lng'));

          // Coordenadas por defecto (La Paz)
          if (isNaN(lat) || isNaN(lng)) {
              lat = -16.5;
              lng = -68.15;
          }

          let pos = { lat: lat, lng: lng };

          // 🔥 LIMPIAR mapa anterior
          if (modalMap !== null) {
              modalMap = null;
          }

          // Crear nuevo mapa
          modalMap = new google.maps.Map(document.getElementById("modal-map"), {
              center: pos,
              zoom: 15
          });

          // Crear marcador
          modalMarker = new google.maps.Marker({
              position: pos,
              map: modalMap
          });

      }, 300);

  });
  </script>

