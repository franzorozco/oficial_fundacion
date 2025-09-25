@extends('adminlte::page')

@section('title', $donation->id ? 'Detalle de Donación #' . $donation->id : 'Mostrar Donación')

@section('content_header')
    <h1 class="mb-4">Detalle de la Donación</h1>
@endsection

@section('content')
    {{-- Información General --}}
    <div class="card shadow-sm rounded">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h3 class="card-title mb-0">Información General</h3>
            <a class="btn btn-outline-info btn-sm" href="{{ route('donations-incoming.pdf', $donation->id) }}">
                <i class="fa fa-file-pdf"></i>
            </a>
        </div>

        <div class="card-body bg-light">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Donante Externo:</strong><br>
                    <span class="badge bg-secondary">{{ $donation->externalDonor->names ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Donante Interno:</strong><br>
                    <span class="badge bg-secondary">{{ $donation->user->name ?? 'N/A' }}</span>
                </div>
                
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Recibido por:</strong><br>
                    <span class="badge bg-info">{{ $donation->receivedBy->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-warning text-dark">{{ $donation->status->name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Campaña Asociada:</strong><br>
                    <span class="badge bg-success">{{ $donation->campaign->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Fecha de Donación:</strong><br>
                    <span class="badge bg-dark">{{ $donation->donation_date->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="mb-3">
                <strong>Notas:</strong><br>
                <p class="text-muted">{{ $donation->notes ?? 'Sin notas adicionales.' }}</p>
            </div>
            <form action="{{ route('donations-incoming.accept', $donation->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Aceptar esta donación?')">
                    <i class="fa fa-check"></i> Aceptar
                </button>
            </form>

            <form action="{{ route('donations-incoming.reject', $donation->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Rechazar esta donación?')">
                    <i class="fa fa-times"></i> Rechazar
                </button>
            </form>
        </div>
    </div>

    {{-- Ítems de la Donación --}}
    <div class="card shadow-sm rounded mt-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Ítems de la Donación</h3>
            <div class="d-flex gap-2">
                 <!-- Botón agregar ítem -->
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fas fa-plus"></i> Agregar Ítem
                </button>
                <!-- Botón ver eliminados -->
                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#deletedItemsModal">
                    <i class="fas fa-trash-restore"></i> Ítems Eliminados
                </button>
               
            </div>
        </div>




        <div class="card-body">
            @if ($donation->items->count() > 0)
                <div class="list-group">
                @foreach ($donation->items as $item)
                    <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 rounded shadow-sm">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex align-items-start">
                                @php
                                    $photo = $item->donation_item_photos->first();
                                    $photoUrl = $photo && !empty($photo->photo_url)
                                        ? asset('storage/' . $photo->photo_url)
                                        : asset('storage/items_donations/default-item.png');
                                @endphp
                                <img src="{{ $photoUrl }}"
                                    alt="Imagen del ítem"
                                    class="me-3 rounded"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $item->item_name }}</h5>
                                    <p class="mb-1"><strong>Tipo de donación:</strong> {{ $item->donation_type->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}</p>
                                    <p class="mb-1"><strong>Descripción:</strong> {{ $item->description ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Registrado el:</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalItem{{ $item->id }}">
                                    <i class="fas fa-eye"></i> Ver
                                </button>

                                <a href="{{ route('donation-items.edit', $item->id) }}" class="btn btn-outline-warning btn-sm mb-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('donation-items.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este ítem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal individual para cada ítem -->
                    <div class="modal fade" id="modalItem{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title" id="modalLabel{{ $item->id }}">Detalle del Ítem: {{ $item->item_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nombre:</strong> {{ $item->item_name }}</p>
                                            <p><strong>Tipo de Donación:</strong> {{ $item->donation_type->name ?? 'N/A' }}</p>
                                            <p><strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}</p>
                                            <p><strong>Descripción:</strong> {{ $item->description ?? 'N/A' }}</p>
                                            <p><strong>Registrado el:</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <img src="{{ $photoUrl }}" class="img-fluid rounded border shadow-sm" alt="Imagen del ítem">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    No hay ítems registrados para esta donación.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para agregar ítem -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form action="{{ route('donation-items.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="donation_id" value="{{ $donation->id }}">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="addItemModalLabel">Agregar Ítem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Nombre del Ítem</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="donation_type_id" class="form-label">Tipo de Donación</label>
                        <select name="donation_type_id" class="form-select" required>
                            @foreach(App\Models\DonationType::all() as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="quantity" class="form-label">Cantidad</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>
                        <div class="col">
                            <label for="unit" class="form-label">Unidad</label>
                            <input type="text" name="unit" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Fotografía</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Ítem</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>




    <!-- Modal: Ítems Eliminados -->
<div class="modal fade" id="deletedItemsModal" tabindex="-1" aria-labelledby="deletedItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="deletedItemsModalLabel">Ítems Eliminados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @php
                    $deletedItems = $donation->items()->onlyTrashed()->get();
                @endphp

                @if($deletedItems->isEmpty())
                    <div class="alert alert-info">No hay ítems eliminados para esta donación.</div>
                @else
                    <div class="list-group">
                        @foreach($deletedItems as $item)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start mb-2 rounded shadow-sm">
                                <div>
                                    <h5 class="mb-1">{{ $item->item_name }}</h5>
                                    <p class="mb-0"><strong>Tipo:</strong> {{ $item->donation_type->name ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong>Cantidad:</strong> {{ $item->quantity }} {{ $item->unit }}</p>
                                </div>
                                <div class="text-end">
                                    <form action="{{ route('donation-items.restore', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm" onclick="return confirm('¿Restaurar este ítem?')">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>

                                    <form action="{{ route('donation-items.forceDelete', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar permanentemente este ítem? Esta acción no se puede deshacer.')">
                                            <i class="fas fa-times"></i> Eliminar Definitivamente
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
