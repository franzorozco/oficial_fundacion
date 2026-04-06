@extends('adminlte::page')

@section('title', 'Donaciones entrantes')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Donaciones Entrantes</h1>
</div>
@endsection

@section('content')

{{-- ================= FILTROS Y BÚSQUEDA ================= --}}
<form method="GET" action="{{ route('donations-incoming.index') }}">
<div class="card shadow-sm">

    {{-- SUCCESS --}}
    @if ($message = Session::get('success'))
    <div class="alert alert-success border-0 shadow-sm mt-3 mx-3">
        <i class="fas fa-check-circle"></i> {{ $message }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Listado de Donaciones</h5>

            <div class="d-flex gap-2">
                @can('donations-incoming.filtrar')
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-toggle="collapse" data-target="#filtrosCollapse">
                    <i class="fas fa-sliders-h"></i> Filtros
                </button>
                @endcan

                <a href="{{ route('donations-incoming.pdf.all') }}" class="btn btn-outline-info btn-sm" target="_blank">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>

                <a href="{{ route('donations-incoming.history') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-history"></i> Historial
                </a>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="p-3 bg-light border-bottom">
        <div class="row">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control form-control-lg"
                       placeholder="Buscar..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="collapse border-bottom" id="filtrosCollapse">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <label>Recibido por</label>
                    <input type="text" name="received_by" class="form-control"
                           value="{{ request('received_by') }}">
                </div>

                <div class="col-md-3">
                    <label>Tipo</label>

                    <div>
                        <input type="checkbox" name="filter[]" value="externo"
                        {{ in_array('externo', (array) request('filter')) ? 'checked' : '' }}>
                        Externo
                    </div>

                    <div>
                        <input type="checkbox" name="filter[]" value="registrado"
                        {{ in_array('registrado', (array) request('filter')) ? 'checked' : '' }}>
                        Registrado
                    </div>
                </div>

                <div class="col-md-3">
                    <label>Fechas</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control mt-2"
                           value="{{ request('end_date') }}">
                </div>

                <div class="col-md-3">
                    <label>Orden</label>
                    <select name="order_by" class="form-select">
                        <option value="">Sin orden</option>
                        <option value="month_asc">Mes ↑</option>
                        <option value="month_desc">Mes ↓</option>
                        <option value="estado">Estado</option>
                        <option value="items_desc">Más ítems</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100 mt-3">Aplicar</button>
                    <a href="{{ route('donations-incoming.index') }}"
                       class="btn btn-outline-secondary w-100 mt-2">
                        Limpiar
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
</form>

{{-- ================= TABLA ================= --}}
@php
function estadoColorClassById($id) {
    return match ($id) {
        1 => 'bg-warning text-dark',
        2 => 'bg-secondary text-white',
        3 => 'bg-success text-white',
        4 => 'bg-danger text-white',
        default => 'bg-light text-dark',
    };
}
@endphp

<div class="card mt-3 shadow-sm">
<div class="card-body p-0">

<table class="table table-hover mb-0">
<thead>
<tr>
    <th>#</th>
    <th>Donante</th>
    <th>Usuario</th>
    <th>Receptor</th>
    <th>Estado</th>
    <th>Campaña</th>
    <th>Fecha</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
@forelse ($donations as $donation)
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $donation->externalDonor->names ?? '—' }}</td>
    <td>{{ $donation->user->name ?? '—' }}</td>
    <td>{{ $donation->receivedBy->name ?? '—' }}</td>

    <td>
        <span class="badge {{ estadoColorClassById($donation->status->id ?? 0) }}">
            {{ $donation->status->name ?? '—' }}
        </span>
    </td>

    <td>{{ $donation->campaign->name ?? '—' }}</td>
    <td>{{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y H:i') }}</td>

    <td>
        <div class="d-flex gap-1">

            <a href="{{ route('donations-incoming.show', $donation->id) }}"
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye"></i>
            </a>

            <a href="{{ route('donations-incoming.pdf', $donation->id) }}"
               class="btn btn-outline-info btn-sm">
                <i class="fas fa-file-pdf"></i>
            </a>

            {{-- ACEPTAR --}}
            <form method="POST" action="{{ route('donations-incoming.accept', $donation->id) }}">
                @csrf
                <button class="btn btn-outline-success btn-sm"
                        onclick="return confirm('¿Aceptar donación?')">
                    <i class="fas fa-check"></i>
                </button>
            </form>

            {{-- RECHAZAR --}}
            <form method="POST" action="{{ route('donations-incoming.reject', $donation->id) }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('¿Rechazar donación?')">
                    <i class="fas fa-times"></i>
                </button>
            </form>

        </div>
    </td>

</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-4">No hay datos</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>

{{-- PAGINACIÓN --}}
<div class="mt-3">
    {{ $donations->withQueryString()->links() }}
</div>

@endsection