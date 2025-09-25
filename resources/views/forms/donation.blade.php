@extends('layouts.nav')

<style>
        body {
            background: linear-gradient(to right, #39b5eb, #cf1d86, #db7fb5);
            background-size: 200% 200%;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .cajaform {
            margin-top: 80px !important;
        }

</style>
@section('contentprin')

<div class="cajaform max-w-5xl mx-auto p-10 bg-white shadow-md rounded-xl mt-[150px]">

  <h2 class="text-2xl font-semibold mb-6 text-gray-800">Formulario de Donación</h2>

  <form action="{{ route('donation.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">

    @csrf
    <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

    <div>
      <label class="block text-gray-700 font-medium mb-2">Nombre de la donación</label>
      <input type="text" name="name_donation" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
      <label class="block text-gray-700 font-medium mb-2">Fecha</label>
      <input type="date" name="donation_date" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
      <label class="block text-gray-700 font-medium mb-2">Notas</label>
      <textarea name="notes" rows="3"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
    </div>

    <div>
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Ítems de Donación</h3>
      <div id="items" class="space-y-6">
        <div class="item p-4 border border-gray-200 rounded-lg shadow-md bg-white">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Imagen o botón -->
                <div class="w-full md:w-1/3 relative">
                    <label class="block text-gray-600 mb-2">Imagen del ítem</label>
                    <div class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 relative group">
                        <input type="file" accept="image/*" name="items[0][photo]" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(event, 0)">
                        <span class="text-gray-400 group-hover:text-gray-600">Haz clic para subir imagen</span>
                        <img id="preview-0" class="absolute inset-0 w-full h-full object-cover hidden" />
                    </div>
                </div>

                <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-600 mb-1">Tipo de donación</label>
                        <select name="items[0][donation_type_id]" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Seleccionar</option>
                        @foreach($donationTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Nombre del ítem</label>
                        <input type="text" name="items[0][item_name]" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Cantidad</label>
                        <input type="number" name="items[0][quantity]" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Unidad</label>
                        <select name="items[0][unit]" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Seleccionar</option>
                            <option value="kg">kg</option>
                            <option value="litros">litros</option>
                            <option value="unidades">unidades</option>
                            <option value="cajas">cajas</option>
                            <option value="otros">otros</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-600 mb-1">Descripción</label>
                        <textarea name="items[0][description]" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <button type="button" onclick="addItem()"
        class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition">
        + Agregar otro ítem
      </button>
    </div>
    <div>
      <button type="submit"
        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
        Enviar Donación
      </button>
    </div>
  </form>
</div>

<script>
let itemIndex = 1;

function previewImage(event, index) {
  const input = event.target;
  const preview = document.getElementById(`preview-${index}`);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.classList.remove('hidden');
      preview.classList.add('block');
      input.previousElementSibling?.classList?.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function removeItem(button) {
  button.closest('.item').remove();
}

function addItem() {
  const container = document.getElementById('items');
  const template = `
  <div class="item p-4 border border-gray-200 rounded-lg shadow-md bg-white relative mt-4">
    <button type="button" onclick="removeItem(this)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
      Eliminar ítem ✖
    </button>
    <div class="flex flex-col md:flex-row gap-6">
      <div class="w-full md:w-1/3 relative">
        <label class="block text-gray-600 mb-2">Imagen del ítem</label>
        <div class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50 relative group">
          <input type="file" accept="image/*" name="items[${itemIndex}][photo]" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(event, ${itemIndex})">
          <span class="text-gray-400 group-hover:text-gray-600">Haz clic para subir imagen</span>
          <img id="preview-${itemIndex}" class="absolute inset-0 w-full h-full object-cover hidden" />
        </div>
      </div>

      <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-600 mb-1">Tipo de donación</label>
          <select name="items[${itemIndex}][donation_type_id]" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Seleccionar</option>
            @foreach($donationTypes as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-gray-600 mb-1">Nombre del ítem</label>
          <input type="text" name="items[${itemIndex}][item_name]" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-gray-600 mb-1">Cantidad</label>
          <input type="number" name="items[${itemIndex}][quantity]" class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        <div>
          <label class="block text-gray-600 mb-1">Unidad</label>
          <select name="items[${itemIndex}][unit]" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            <option value="">Seleccionar</option>
            <option value="kg">kg</option>
            <option value="litros">litros</option>
            <option value="unidades">unidades</option>
            <option value="cajas">cajas</option>
            <option value="otros">otros</option>
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="block text-gray-600 mb-1">Descripción</label>
          <textarea name="items[${itemIndex}][description]" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
        </div>
      </div>
    </div>
  </div>`;
  container.insertAdjacentHTML('beforeend', template);
  itemIndex++;
}
</script>

@endsection
@guest
    <!-- Modal solo si el usuario NO ha iniciado sesión -->
    <div 
        x-data="{ showModal: true }"
        x-show="showModal"
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-60"
    >
        <div class="bg-white p-8 rounded-xl shadow-lg w-[90%] max-w-md text-center">
            <h2 class="text-xl font-semibold text-red-600 mb-4">¡Atención!</h2>
            <p class="text-gray-700 mb-6">Debes iniciar sesión o registrarte para continuar con la donación.</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Iniciar Sesión</a>
                <a href="{{ route('register') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Registrarse</a>
            </div>
        </div>
    </div>

    <!-- Alpine.js para el modal -->
    <script src="//unpkg.com/alpinejs" defer></script>
@endguest
