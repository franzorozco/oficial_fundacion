@extends('adminlte::page')

@section('title', 'Donations')

@section('content_header')
    <h1>{{ __('Donations') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    {{ __('Donations') }}
                </span>
                <div class="mb-3">
                    <form action="{{ route('donations.index') }}" method="GET" class="form-inline d-flex" role="search">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Buscar donaciones..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    </form>
                </div>
                <a href="{{ route('donations.create') }}" class="btn btn-primary btn-sm" data-placement="left">
                    {{ __('Create New') }}
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>Donante externo</th>
                            <th>Usuario registrado</th>
                            <th>Recibido por</th>
                            <th>Estado</th>
                            <th>Campaña</th>
                            <th>Fecha</th>
                            <th>Notas</th>
                            <th></th>
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
                                    <form action="{{ route('donations.destroy', $donation->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('donations.show', $donation->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('donations.edit', $donation->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! $donations->withQueryString()->links() !!}
@endsection
