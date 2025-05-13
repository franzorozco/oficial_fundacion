@extends('adminlte::page')

@section('title', 'Donations')

@section('content_header')
    <h1>{{ __('Donations') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <span id="card_title" class="h5 m-0">
                    {{ __('Donations') }}
                </span>

                <form action="{{ route('donations.index') }}" method="GET" class="d-flex flex-wrap gap-2" role="search">
                    <input type="text" name="search" class="form-control" placeholder="Buscar donaciones..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary btn-sm" type="submit">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </form>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('donations.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-plus"></i> {{ __('Create New') }}
                    </a>
                    <a href="{{ route('donations.pdf.all') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-file-pdf"></i> Descargar PDF de Todas
                    </a>
                    <a href="{{ route('donations.trashed') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fa fa-trash"></i> Ver eliminados
                    </a>

                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p class="mb-0">{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
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
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('donations.show', $donation->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('donations.edit', $donation->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-info btn-sm" href="{{ route('donations.pdf', $donation->id) }}">
                                            <i class="fa fa-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('donations.destroy', $donation->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {!! $donations->withQueryString()->links() !!}
    </div>
@endsection
