@extends('adminlte::page')

@section('title', 'Detalle Donación #' . $donation->id)

@section('content_header')
<div class="d-flex justify-content-between align-items-center">

    <div>
        <h1 class="mb-0">Detalle de la Donación</h1>
        <small class="text-muted">
            Información completa, ítems asociados y gestión de registros de la donación seleccionada.
        </small>
    </div>

    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left"></i> Volver al listado
    </a>

</div>
@endsection


@section('content')

<div class="row">

    <!-- ===================== INFO GENERAL ===================== -->
    <div class="col-lg-4 mb-3">

        <div class="card shadow-sm h-100">

            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">Información General</h6>
                <small class="text-muted">
                    Datos principales del registro de la donación.
                </small>
            </div>

            <div class="card-body small">

                <div class="mb-3">
                    <label class="text-muted">Donante Externo</label>
                    <div class="fw-semibold">
                        {{ $donation->externalDonor->names ?? 'No registrado' }}
                    </div>
                    <small class="text-muted">Persona o entidad externa que realizó la donación.</small>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Usuario Registrado</label>
                    <div class="fw-semibold">
                        {{ $donation->user->name ?? 'No asignado' }}
                    </div>
                    <small class="text-muted">Usuario interno que registró la donación en el sistema.</small>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Recibido por</label>
                    <div class="fw-semibold text-primary">
                        {{ $donation->receivedBy->name ?? 'Pendiente' }}
                    </div>
                    <small class="text-muted">Responsable de la recepción física de la donación.</small>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Estado actual</label>
                    <div>
                        <span class="badge bg-warning text-dark">
                            {{ $donation->status->name ?? 'Sin estado' }}
                        </span>
                    </div>
                    <small class="text-muted">Indica la situación actual del proceso de la donación.</small>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Campaña asociada</label>
                    <div class="fw-semibold text-success">
                        {{ $donation->campaign->name ?? 'No vinculada' }}
                    </div>
                    <small class="text-muted">Campaña a la que pertenece esta donación.</small>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Fecha de donación</label>
                    <div class="fw-semibold">
                        {{ optional($donation->donation_date)->format('d/m/Y') ?? 'No definida' }}
                    </div>
                    <small class="text-muted">Fecha en la que se realizó la donación.</small>
                </div>

                <div>
                    <label class="text-muted">Notas adicionales</label>
                    <div class="text-muted">
                        {{ $donation->notes ?? 'No se registraron observaciones.' }}
                    </div>
                    <small class="text-muted">Información complementaria registrada manualmente.</small>
                </div>

            </div>
        </div>

    </div>


    <!-- ===================== ITEMS ===================== -->
<div class="col-lg-8">

    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

            <div>
                <h6 class="mb-0 fw-bold">Ítems de la Donación</h6>
                <small class="text-muted">
                    Lista de productos, bienes o elementos registrados en esta donación.
                </small>
            </div>

            <div class="d-flex gap-2">

                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fa fa-plus"></i> Agregar ítem
                </button>

                


                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deletedItemsModal">
                    <i class="fa fa-trash"></i> Ítems eliminados
                </button>

                @can('donations.verpdf')
                <a href="{{ route('donations.pdf', $donation->id) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                    <i class="fa fa-file-pdf"></i>
                </a>
                @endcan

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body">

            @if ($donation->items->count())

                <div class="row g-3">

                    @foreach ($donation->items as $item)

                        @php
                            $photo = $item->donation_item_photos->first();
                            $photoUrl = $photo && !empty($photo->photo_url)
                                ? asset('storage/items_donations/' . $photo->photo_url)
                                : asset('storage/items_donations/default-item.png');
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">

                                <img src="{{ $photoUrl }}"
                                        class="card-img-top"
                                        onerror="this.onerror=null; this.src='{{ asset('storage/items_donations/default-item.png') }}';">
                                <div class="card-body d-flex flex-column">

                                    <h6 class="card-title fw-bold">{{ $item->item_name }}</h6>
                                    <p class="text-muted mb-1"><i class="fa fa-tag"></i> {{ $item->donation_type->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}</p>
                                    <p class="text-muted small mb-2">{{ Str::limit($item->description ?? 'Sin descripción', 60) }}</p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">

                                        <button class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalItem{{ $item->id }}">
                                            <i class="fa fa-eye"></i> Ver
                                        </button>

                                        <div class="btn-group">
                                            <a href="{{ route('donation-items.edit', $item->id) }}"
                                               class="btn btn-outline-success btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('donation-items.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('¿Eliminar ítem?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- MODAL ITEM -->
                        <div class="modal fade" id="modalItem{{ $item->id }}">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <div>
                                            <h5 class="modal-title">{{ $item->item_name }}</h5>
                                            <small class="text-muted">Detalle completo del ítem</small>
                                        </div>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-md-6">

                                                <p><strong>Tipo:</strong> {{ $item->donation_type->name ?? 'N/A' }}</p>
                                                <p><strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}</p>
                                                <p><strong>Descripción:</strong></p>
                                                <p class="text-muted">{{ $item->description ?? 'Sin descripción' }}</p>
                                                <p><strong>Fecha de registro:</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>

                                            </div>

                                            <div class="col-md-6 text-center">
                                                <img src="{{ $photoUrl }}" class="img-fluid rounded border shadow-sm">
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>

            @else
                <div class="text-center text-muted py-5">
                    <i class="fa fa-box-open fa-3x mb-3"></i>
                    <p class="mb-0 fw-semibold">No hay ítems registrados</p>
                    <small>Agrega nuevos ítems haciendo clic en "Agregar ítem".</small>
                </div>
            @endif

        </div>

    </div>

</div>
</div>


<!-- ===================== MODAL ADD ITEM ===================== -->
<div class="modal fade" id="addItemModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <form action="{{ route('donation-items.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf

            <input type="hidden" name="donation_id" value="{{ $donation->id }}">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Registrar nuevo ítem</h5>
                    <small class="text-muted">Complete los datos del nuevo elemento donado</small>
                </div>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Nombre del ítem</label>
                    <input type="text" name="item_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tipo de donación</label>
                    <select name="donation_type_id" class="form-control">
                        @foreach(App\Models\DonationType::all() as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Cantidad</label>
                        <input type="number" name="quantity" class="form-control">
                    </div>
                    <div class="col">
                        <label>Unidad</label>
                        <input type="text" name="unit" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Fotografía</label>
                    <input type="file" name="photo" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Guardar ítem</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </form>

    </div>
</div>


<!-- ===================== MODAL ELIMINADOS ===================== -->
<div class="modal fade" id="deletedItemsModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Ítems eliminados</h5>
                    <small class="text-muted">Elementos que fueron eliminados pero pueden ser restaurados</small>
                </div>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @php
                    $deletedItems = $donation->items()->onlyTrashed()->get();
                @endphp

                @forelse($deletedItems as $item)

                    <div class="border rounded p-2 mb-2 d-flex justify-content-between">

                        <div>
                            <div class="fw-semibold">{{ $item->item_name }}</div>
                            <small class="text-muted">
                                {{ $item->quantity }} {{ $item->unit }}
                            </small>
                        </div>

                        <div class="btn-group">

                            <form action="{{ route('donation-items.restore', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success btn-sm">Restaurar</button>
                            </form>

                            <form action="{{ route('donation-items.forceDelete', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar definitivamente</button>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted">
                        No hay ítems eliminados actualmente.
                    </div>

                @endforelse

            </div>

        </div>

    </div>
</div>

@endsection

@section('css')
    <style>
        .list-group-item h5 {
            font-size: 1.1rem;
        }

        .list-group-item p {
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('js')
    {{-- Incluye Bootstrap JS si aún no está cargado --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
