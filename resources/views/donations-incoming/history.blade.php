@extends('adminlte::page')

@section('title', 'Historial de Donaciones')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Historial de Donaciones</h1>
</div>
@endsection

@section('content')

{{-- ================= FILTROS (FORM INDEPENDIENTE) ================= --}}
<form method="GET" action="{{ route('donations-incoming.history') }}">

<div class="card shadow-sm">

    {{-- ================= HEADER ================= --}}
    <div class="card-header bg-white border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <h5 class="mb-0 fw-bold">Donaciones Aceptadas / Rechazadas</h5>

            <div class="d-flex gap-2">

                <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-toggle="collapse"
                        data-target="#filtrosCollapse">
                    <i class="fas fa-sliders-h"></i> Filtros
                </button>

                <a href="{{ route('donations-incoming.export-pdf', request()->all()) }}"
                   class="btn btn-outline-danger btn-sm"
                   target="_blank">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>

                <a href="{{ route('donations-incoming.index') }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

            </div>
        </div>
    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="p-3 bg-light border-bottom">
        <div class="row align-items-center">

            <div class="col-md-10">
                <input type="text"
                       name="search"
                       class="form-control form-control-lg"
                       placeholder="Buscar por donante, usuario, notas..."
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-2 mt-2 mt-md-0">
                <button type="submit" class="btn btn-primary w-100">
                    Buscar
                </button>
            </div>

        </div>
    </div>

    {{-- ================= FILTROS ================= --}}
    <div class="collapse border-bottom" id="filtrosCollapse">
        <div class="card-body bg-white">

            <div class="row g-3">

                <div class="col-md-3">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold">
                            <i class="fas fa-check-circle text-primary me-1"></i> Estado
                        </label>

                        <select name="status" class="form-select mt-2">
                            <option value="">Todos</option>
                            <option value="Aceptada" {{ request('status') == 'Aceptada' ? 'selected' : '' }}>Aceptada</option>
                            <option value="Rechazada" {{ request('status') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold">
                            <i class="fas fa-user text-primary me-1"></i> Decisión por
                        </label>

                        <select name="decision_by" class="form-select mt-2">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('decision_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 border rounded h-100">
                        <label class="fw-semibold">
                            <i class="fas fa-calendar text-primary me-1"></i> Fechas
                        </label>

                        <input type="date" name="start_date"
                               class="form-control mt-2"
                               value="{{ request('start_date') }}">

                        <input type="date" name="end_date"
                               class="form-control mt-2"
                               value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-end">
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-search"></i> Aplicar
                    </button>

                    <a href="{{ route('donations-incoming.history') }}"
                       class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

</form>

{{-- ================= TABLA (FUERA DEL FORM) ================= --}}
<div class="card shadow-sm mt-3">

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Donante</th>
                        <th>Usuario</th>
                        <th>Decisión</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Notas</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($donations as $donation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $donation->externalDonor->names ?? '-' }}</td>
                            <td>{{ $donation->user->name ?? '-' }}</td>
                            <td>{{ $donation->receivedBy->name ?? '-' }}</td>

                            <td>
                                @if ($donation->status->name === 'Aceptada')
                                    <span class="badge bg-success">Aceptada</span>
                                @elseif ($donation->status->name === 'Rechazada')
                                    <span class="badge bg-danger">Rechazada</span>
                                @else
                                    <span class="badge bg-secondary">{{ $donation->status->name }}</span>
                                @endif
                            </td>

                            <td>{{ $donation->donation_date }}</td>
                            <td>{{ $donation->notes ?? '-' }}</td>

                            <td class="text-center">
                                <form action="{{ route('donations-incoming.reconsiderar', $donation->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Reconsiderar esta donación?')">
                                    @csrf

                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="alert alert-info m-3">
                                    No hay donaciones en el historial.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <div class="card-footer bg-white">
        {!! $donations->links() !!}
    </div>

</div>

@endsection