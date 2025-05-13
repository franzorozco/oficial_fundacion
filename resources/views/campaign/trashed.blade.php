@extends('adminlte::page')

@section('title', 'Campaigns Eliminadas')

@section('content_header')
    <h1>Campañas Eliminadas</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('campaigns.trashed') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button class="btn btn-outline-primary btn-sm" type="submit">
                <i class="fa fa-search"></i> Buscar
            </button>
        </form>
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
                        <th>Creator</th>
                        <th>Name</th>
                        <th>Eventos</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaigns as $campaign)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $campaign->user->name ?? 'N/A' }}</td>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ $campaign->events_count }}</td>
                            <td>{{ $campaign->description }}</td>
                            <td>{{ $campaign->start_date }}</td>
                            <td>{{ $campaign->end_date }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <form action="{{ route('campaigns.restore', $campaign->id) }}" method="POST" onsubmit="return confirm('¿Restaurar esta campaña?');">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('campaigns.forceDelete', $campaign->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-times"></i>
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
    {!! $campaigns->withQueryString()->links() !!}
</div>
@endsection
