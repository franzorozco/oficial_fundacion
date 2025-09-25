@extends('layouts.nav')
<style>
html, body {
  height: 100%;
  margin: 0;
  scroll-behavior: smooth;
  overflow: hidden;
  font-family: 'Open Sans', sans-serif;
}

.vertical-slider {
  height: 100vh;
  width: 100%;
  overflow: hidden;
  position: relative;
}

.slide {
  height: 100vh;
  width: 100%;
  flex-shrink: 0;
  scroll-snap-align: start;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #F0F4F8;
  padding: 2rem;
}

.slide:nth-child(even) {
  background-color: #FFFFFF;
}

.slide-content {
  max-width: 800px;
  text-align: center;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 2rem;
  border-radius: 12px;
}


.slide h2 {
  font-size: 2.5rem;
  color: #003366;
  margin-bottom: 1rem;
}

.slide p {
  color: #444;
  font-size: 1.125rem;
  line-height: 1.6;
}

.btn-donar {
  display: inline-block;
  background-color: #00ADEF;
  color: #FFFFFF;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-weight: 600;
  margin-top: 1.25rem;
  transition: background-color 0.3s;
  text-decoration: none;
}

.btn-donar:hover {
  background-color: #0088BE;
}

.nav-buttons {
  position: fixed;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.nav-buttons button {
  background-color: #003366;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.nav-buttons button:hover {
  background-color: #005599;
}
</style>

@section('contentprin')
<div x-data="campaignActions()" class="vertical-slider flex flex-col relative" style="overflow-y: auto; scroll-snap-type: y mandatory;">
    @foreach($campaigns as $index => $campaign)
      <section class="slide" 
              :id="'slide-' + {{ $index }}" 
              style="background-image: url('{{ asset('storage/campaigns/' . basename($campaign->foto)) }}'); background-size: cover; background-position: center;">

      <div class="slide-content">
        <h2>{{ $campaign->name }}</h2>
        <p>{{ $campaign->description }}</p>

        <!-- Botón Quiero Participar -->
        <a href="#"
           @click.prevent="handleParticipate({{ $campaign->id }})"
           class="btn-donar">
           Quiero Participar
        </a>
        <!-- Botón Participa Donando -->
        <a href="{{ route('donation.form', ['campaign_id' => $campaign->id]) }}" 
          class="btn-donar">
          Participa Donando
        </a>

      </div>
    </section>
  @endforeach

  <!-- Modales -->
  <!-- Modal: Sesión no iniciada -->
  <div x-show="showLoginModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl">
      <p class="mb-4">Debes iniciar sesión para continuar.</p>
      <div class="flex justify-end gap-4">
        <button @click="showLoginModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
        <a href="/login" class="px-4 py-2 bg-blue-500 text-white rounded">Iniciar Sesión</a>
      </div>
    </div>
  </div>

  <!-- Modal: Usuario sin rol de voluntario -->
  <div x-show="showRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl">
      <p class="mb-4">Tu cuenta no esta verificada para realizar tareas de Voluntario, por favor.</p>
      <div class="flex justify-end gap-4">
        <button @click="showRoleModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
        <a href="/mas-informacion" class="px-4 py-2 bg-yellow-500 text-white rounded">Solicitar mi verificacion</a>
      </div>
    </div>
  </div>

</div>

<!-- Navegación vertical -->
<div x-data="{ total: {{ count($campaigns) }}, index: 0 }" class="nav-buttons">
  <button @click="index = Math.max(index - 1, 0); document.querySelectorAll('.slide')[index].scrollIntoView({behavior: 'smooth'})">↑</button>
  <button @click="index = Math.min(index + 1, total - 1); document.querySelectorAll('.slide')[index].scrollIntoView({behavior: 'smooth'})">↓</button>
</div>
@endsection

<script>
  const isLoggedIn = @json(Auth::check());
  const isVolunteer = @json(Auth::check() && auth()->user()->hasRole('Voluntario'));
</script>


<script>
function campaignActions() {
  return {
    showLoginModal: false,
    showRoleModal: false,

    handleParticipate(campaignId) {
      if (!isLoggedIn) {
        this.showLoginModal = true;
      } else if (!isVolunteer) {
        this.showRoleModal = true;
      } else {
        window.location.href = `/volunteer/campaign/${campaignId}`;
      }
    },


    handleDonate(campaignId) {
      if (!isLoggedIn) {
        this.showLoginModal = true;
      } else {
        window.location.href = `/donation/form?campaign_id=${campaignId}`;
      }
    }

  }
}

</script>
