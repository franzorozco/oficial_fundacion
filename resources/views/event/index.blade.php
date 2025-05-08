@extends('adminlte::page')

@section('title', 'Events')

@section('content_header')
    <h1>{{ __('Events') }}</h1>
@stop
 
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Events') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm" data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                            </div>
                            
                        </div>
                        
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <!-- Barra de búsqueda y filtros -->
                    <div class="card-body pt-0">
                        <form method="GET" action="{{ route('events.index') }}" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">{{ __('Event Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ request('name') }}" placeholder="Search by name...">
                                </div>

                                <div class="col-md-4">
                                    <label for="campaign_id" class="form-label">{{ __('Campaign') }}</label>
                                    <select name="campaign_id" id="campaign_id" class="form-control">
                                        <option value="">{{ __('All Campaigns') }}</option>
                                        @foreach(App\Models\Campaign::all() as $campaign)
                                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                                {{ $campaign->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> {{ __('Filter') }}
                                    </button>
                                    <a href="{{ route('events.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-times"></i> {{ __('Clear') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Campaign Id</th>
                                        <th>Creator Id</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Event Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach ($events as $event)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $event->campaign->name ?? '—' }}</td>
            <td>{{ $event->user->name ?? '—' }}</td>
            <td>{{ $event->name }}</td>
            <td>{{ $event->description }}</td>
            <td>{{ $event->event_date }}</td>
            <td>
                <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                    <a class="btn btn-sm btn-primary" href="{{ route('events.show', $event->id) }}">
                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                    </a>
                    <a class="btn btn-sm btn-success" href="{{ route('events.edit', $event->id) }}">
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
                {!! $events->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
