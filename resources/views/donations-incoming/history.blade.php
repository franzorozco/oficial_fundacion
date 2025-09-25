@extends('adminlte::page')

@section('title', 'Historial de Donaciones')

@section('content_header')
    <h1>Historial de Donaciones Aceptadas / Rechazadas</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body bg-white">
        <div class="table-responsive">
            <form method="GET" action="{{ route('donations-incoming.history') }}" class="mb-3 d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, notas, receptor..." value="{{ request('search') }}">
                
                <select name="status" class="form-select">
                    <option value="">-- Estado --</option>
                    <option value="Aceptada" {{ request('status') == 'Aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="Rechazada" {{ request('status') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
                <select name="decision_by" class="form-select">
                    <option value="">-- Todos los usuarios --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('decision_by') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>


                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Filtrar
                </button>
                <a href="{{ route('donations-incoming.history') }}" class="btn btn-secondary">
                    <i class="fa fa-times"></i> Limpiar
                </a>
                <a href="{{ route('donations-incoming.export-pdf', request()->all()) }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf"></i> Exportar PDF
                </a>

            </form>

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Donante externo</th>
                        <th>Usuario registrado</th>
                        <th>Decisión tomada por</th>

                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $i => $donation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $donation->externalDonor->names ?? '-' }}</td>
                            <td>{{ $donation->user->name ?? '-' }}</td>
                            <td>{{ $donation->receivedBy->name ?? '-' }}</td>
                            <td>{{ $donation->status->name }}</td>
                            <td>{{ $donation->donation_date }}</td>
                            <td>{{ $donation->notes }}</td>
                            <td>
                                <form action="{{ route('donations-incoming.reconsider', $donation->id) }}" method="POST" onsubmit="return confirm('¿Reconsiderar esta decisión?')">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-undo"></i> Reconsiderar
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if ($donation->status->name === 'Aceptada')
                                    <span class="badge bg-success">Aceptada</span>
                                @elseif ($donation->status->name === 'Rechazada')
                                    <span class="badge bg-danger">Rechazada</span>
                                @else
                                    <span class="badge bg-secondary">{{ $donation->status->name }}</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($donations->isEmpty())
                <div class="alert alert-info" role="alert">
                    No hay donaciones aceptadas o rechazadas.
                </div>
            @endif
            <div class="mt-3">
                {!! $donations->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
