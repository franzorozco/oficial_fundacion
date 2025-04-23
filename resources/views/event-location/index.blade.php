@extends('adminlte::page')

@section('title', 'Event Locations')

@section('content_header')
    <h1>{{ __('Event Locations') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Event Locations') }}</span>

                            <div class="float-right">
                                <a href="{{ route('event-locations.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Event Id</th>
                                        <th>Location Name</th>
                                        <th>Address</th>
                                        <th>Latitud</th>
                                        <th>Longitud</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eventLocations as $eventLocation)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $eventLocation->event_id }}</td>
                                            <td>{{ $eventLocation->location_name }}</td>
                                            <td>{{ $eventLocation->address }}</td>
                                            <td>{{ $eventLocation->latitud }}</td>
                                            <td>{{ $eventLocation->longitud }}</td>
                                            <td>
                                                <form action="{{ route('event-locations.destroy', $eventLocation->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('event-locations.show', $eventLocation->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('event-locations.edit', $eventLocation->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
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
                {!! $eventLocations->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
