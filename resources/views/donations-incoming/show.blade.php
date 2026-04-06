@extends('adminlte::page')

@section('title', 'Detalle de Donación #' . $donation->id)

@section('content_header')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

    <div>
        <h2 class="mb-0 fw-bold">
            <i class="fas fa-hand-holding-heart text-primary me-2"></i>
            Detalle de Donación #{{ $donation->id }}
        </h2>
        <small class="text-muted">
            Información completa de la donación y sus ítems asociados
        </small>
    </div>

    <a class="btn btn-outline-info btn-sm"
       href="{{ route('donations-incoming.pdf', $donation->id) }}">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </a>

</div>
@endsection


@section('content')

{{-- ================= RESUMEN GENERAL ================= --}}
<div class="card border-0 shadow-sm mt-3">

    <div class="card-header bg-white border-bottom">
        <strong>Resumen de la donación</strong>
        <small class="text-muted d-block">
            Información principal del registro
        </small>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Donante externo</small>
                    <div class="fw-bold">
                        {{ $donation->externalDonor->names ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Donante interno</small>
                    <div class="fw-bold">
                        {{ $donation->user->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Recibido por</small>
                    <div class="fw-bold">
                        {{ $donation->receivedBy->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Estado</small>
                    <div>
                        <span class="badge bg-warning text-dark">
                            {{ $donation->status->name ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Campaña</small>
                    <div class="fw-bold">
                        {{ $donation->campaign->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 border rounded h-100">
                    <small class="text-muted">Fecha</small>
                    <div class="fw-bold">
                        {{ $donation->donation_date->format('d/m/Y') }}
                    </div>
                </div>
            </div>

        </div>

        {{-- NOTAS --}}
        <div class="mt-4">
            <strong>Notas</strong>
            <p class="text-muted mb-0">
                {{ $donation->notes ?? 'Sin notas adicionales.' }}
            </p>
        </div>

        {{-- ACCIONES --}}
        <div class="mt-3 d-flex gap-2">

            <form action="{{ route('donations-incoming.accept', $donation->id) }}" method="POST">
                @csrf
                <button class="btn btn-outline-success btn-sm"
                        onclick="return confirm('¿Aceptar esta donación?')">
                    <i class="fas fa-check"></i> Aceptar
                </button>
            </form>

            <form action="{{ route('donations-incoming.reject', $donation->id) }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('¿Rechazar esta donación?')">
                    <i class="fas fa-times"></i> Rechazar
                </button>
            </form>

        </div>

    </div>
</div>


{{-- ================= ÍTEMS ================= --}}
<div class="card border-0 shadow-sm mt-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <div>
            <strong>Ítems de la donación</strong>
            <small class="text-muted d-block">
                Productos o elementos asociados a esta donación
            </small>
        </div>

        <div class="d-flex gap-2">

            <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#addItemModal">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button class="btn btn-outline-secondary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#deletedItemsModal">
                <i class="fas fa-trash-restore"></i> Eliminados
            </button>

        </div>

    </div>


    <div class="card-body">

        @if ($donation->items->count() > 0)

            <div class="row g-3">

                @foreach ($donation->items as $item)

                @php
                    $photo = $item->donation_item_photos->first();
                    $photoUrl = $photo && !empty($photo->photo_url)
                        ? asset('storage/items_donations/' . $photo->photo_url)
                        : asset('storage/items_donations/default-item.png');
                @endphp

                <div class="col-md-6 col-lg-4">

                    <div class="card border shadow-sm h-100">

                        <img src="{{ $photoUrl }}"
                             class="card-img-top"
                             style="height: 160px; object-fit: cover;">

                        <div class="card-body">

                            <h5 class="fw-bold">{{ $item->item_name }}</h5>

                            <p class="mb-1 text-muted">
                                {{ $item->donation_type->name ?? 'Sin tipo' }}
                            </p>

                            <p class="mb-1">
                                <strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}
                            </p>

                            <p class="text-muted small">
                                {{ Str::limit($item->description, 60) }}
                            </p>

                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between">

                            <button class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalItem{{ $item->id }}">
                                Ver
                            </button>

                            <div class="d-flex gap-1">

                                <a href="{{ route('donation-items.edit', $item->id) }}"
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('donation-items.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar ítem?')">
                                    @csrf @method('DELETE')

                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        @else

            {{-- EMPTY STATE UX --}}
            <div class="text-center py-5 text-muted">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <div class="fw-semibold">No hay ítems registrados</div>
                <small>Agrega el primer ítem para esta donación</small>
            </div>

        @endif

    </div>
</div>


{{-- ================= MODALES (se mantienen pero más limpios UX) ================= --}}

{{-- ================= MODAL AGREGAR ÍTEM ================= --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <form action="{{ route('donation-items.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="modal-content border-0 shadow-lg rounded-3">

            @csrf
            <input type="hidden" name="donation_id" value="{{ $donation->id }}">

            {{-- HEADER --}}
            <div class="modal-header bg-white border-bottom">
                <div>
                    <h5 class="modal-title fw-bold" id="addItemModalLabel">
                        <i class="fas fa-plus-circle text-success me-1"></i>
                        Agregar Ítem
                    </h5>
                    <small class="text-muted">Registra un nuevo elemento en esta donación</small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del Ítem</label>
                    <input type="text" name="item_name" class="form-control form-control-lg" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo de Donación</label>
                    <select name="donation_type_id" class="form-select form-select-lg" required>
                        @foreach(App\Models\DonationType::all() as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">

                    <div class="col">
                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <div class="col">
                        <label class="form-label fw-semibold">Unidad</label>
                        <input type="text" name="unit" class="form-control">
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Fotografía</label>
                    <input type="file" name="photo" accept="image/*" class="form-control">
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-top">

                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit" class="btn btn-success">
                    Guardar Ítem
                </button>

            </div>

        </form>
    </div>
</div>



<div class="modal fade" id="deletedItemsModal" tabindex="-1" aria-labelledby="deletedItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-3">

            {{-- HEADER --}}
            <div class="modal-header bg-white border-bottom">
                <div>
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-trash-restore text-danger me-1"></i>
                        Ítems Eliminados
                    </h5>
                    <small class="text-muted">Puedes restaurar o eliminar permanentemente</small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                @php
                    $deletedItems = $donation->items()->onlyTrashed()->get();
                @endphp

                @if($deletedItems->isEmpty())

                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-3x mb-2"></i>
                        <div class="fw-semibold">No hay ítems eliminados</div>
                        <small>Todo está en orden</small>
                    </div>

                @else

                    <div class="row g-3">

                        @foreach($deletedItems as $item)

                        <div class="col-md-6">

                            <div class="card border shadow-sm h-100">

                                <div class="card-body">

                                    <h5 class="fw-bold mb-1">
                                        {{ $item->item_name }}
                                    </h5>

                                    <p class="mb-1 text-muted">
                                        <strong>Tipo:</strong>
                                        {{ $item->donation_type->name ?? 'N/A' }}
                                    </p>

                                    <p class="mb-2">
                                        <strong>Cantidad:</strong>
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </p>

                                    <span class="badge bg-danger">
                                        Eliminado
                                    </span>

                                </div>

                                <div class="card-footer bg-white d-flex justify-content-between">

                                    <form action="{{ route('donation-items.restore', $item->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button class="btn btn-outline-success btn-sm"
                                                onclick="return confirm('¿Restaurar este ítem?')">
                                            <i class="fas fa-undo"></i>
                                            Restaurar
                                        </button>

                                    </form>

                                    <form action="{{ route('donation-items.forceDelete', $item->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Eliminar permanentemente?')">
                                            <i class="fas fa-times"></i>
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                        @endforeach


                    </div>

                @endif

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>

    </div>
</div>



@foreach ($donation->items as $item)

<div class="modal fade" id="modalItem{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    {{ $item->item_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                @php
                    $photo = $item->donation_item_photos->first();
                    $photoUrl = $photo && !empty($photo->photo_url)
                        ? asset('storage/items_donations/' . $photo->photo_url)
                        : asset('storage/items_donations/default-item.png');
                @endphp

                <div class="row">

                    <div class="col-md-5 text-center">
                        <img src="{{ $photoUrl }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 250px;">
                    </div>

                    <div class="col-md-7">

                        <p><strong>Tipo:</strong>
                            {{ $item->donation_type->name ?? 'N/A' }}
                        </p>

                        <p><strong>Cantidad:</strong>
                            {{ $item->quantity }} {{ $item->unit }}
                        </p>

                        <p><strong>Descripción:</strong></p>
                        <p class="text-muted">
                            {{ $item->description ?? 'Sin descripción' }}
                        </p>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>

    </div>
</div>

@endforeach

@endsection

@section('css')
    <style>
  .modal-content {
    border-radius: 14px;
}

.modal-header {
    padding: 1rem 1.25rem;
}

.modal-body {
    padding: 1.25rem;
}

.form-control,
.form-select {
    border-radius: 10px;
}

.btn {
    border-radius: 10px;
}
    </style>
@endsection

@section('js')
    {{-- Incluye Bootstrap JS si aún no está cargado --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
