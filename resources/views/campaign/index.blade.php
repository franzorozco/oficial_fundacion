@extends('adminlte::page')

@section('title', 'Campaigns')

@section('content_header')
    <h1>{{ __('Campaigns') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <span id="card_title" class="h5 m-0">
                    {{ __('Campaigns') }}
                </span>

                <form action="{{ route('campaigns.index') }}" method="GET" class="d-flex flex-wrap gap-2" role="search">
                    <input type="text" name="search" class="form-control" placeholder="Buscar campañas..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary btn-sm" type="submit">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </form>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('campaigns.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-plus"></i> {{ __('Create New') }}
                    </a>
                    <a href="{{ route('campaigns.pdf.all', ['search' => request('search')]) }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-file-pdf"></i> Descargar PDF
                    </a>
                    <a href="{{ route('campaigns.trashed') }}" class="btn btn-outline-darck btn-sm">
                        <i class="fa fa-trash-restore"></i> Ver Eliminadas
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
                            <th>Creator</th>
                            <th>Name</th>
                            <th>Eventos</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Hour</th>
                            <th>End Hour</th>
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
                                <td>{{ $campaign->start_hour }}</td>
                                <td>{{ $campaign->end_hour }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('campaigns.show', $campaign->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('campaigns.edit', $campaign->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar?');">
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
        {!! $campaigns->withQueryString()->links() !!}
    </div>
@endsection
