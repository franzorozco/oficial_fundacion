@extends('adminlte::page')

@section('title', 'Donaciones Eliminadas')

@section('content_header')
    <h1>{{ __('Donaciones Eliminadas') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="h5 m-0">Donaciones Eliminadas</span>
            <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-3">
                {{ $message }}
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Donante externo</th>
                            <th>Usuario registrado</th>
                            <th>Recibido por</th>
                            <th>Estado</th>
                            <th>Campaña</th>
                            <th>Fecha</th>
                            <th>Notas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donations as $donation)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $donation->externalDonor->names ?? '-' }}</td>
                                <td>{{ $donation->user->name ?? '-' }}</td>
                                <td>{{ $donation->receivedBy->name ?? '-' }}</td>
                                <td>{{ $donation->status->name ?? '-' }}</td>
                                <td>{{ $donation->campaign->name ?? '-' }}</td>
                                <td>{{ $donation->donation_date }}</td>
                                <td>{{ $donation->notes }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <form method="POST" action="{{ route('donations.restore', $donation->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                <i class="fa fa-undo"></i> Restaurar
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('donations.forceDelete', $donation->id) }}" onsubmit="return confirm('¿Eliminar definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-times"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {!! $donations->links() !!}
            </div>
        </div>
    </div>
@endsection
