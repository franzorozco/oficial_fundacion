@extends('adminlte::page')

@section('title', 'Campaigns')

@section('content_header')
    <h1>{{ __('Campaigns') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span id="card_title">{{ __('Campaigns') }}</span>
            <div class="mb-3">
                <form action="{{ route('campaigns.index') }}" method="GET" class="form-inline d-flex" role="search">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Buscar campañas..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                </form>
            </div>

            <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-sm">
                {{ __('Create New') }}
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
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
                            <th>Actions</th>
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
                                    <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" class="d-inline">
                                        <a class="btn btn-sm btn-primary" href="{{ route('campaigns.show', $campaign->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('campaigns.edit', $campaign->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
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

    {!! $campaigns->withQueryString()->links() !!}
@endsection
