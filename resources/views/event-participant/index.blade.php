@extends('adminlte::page')

@section('title', 'Event Participants')

@section('content_header')
    <h1>{{ __('Event Participants') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Event Participants') }}</span>
                            <div class="float-right">
                                <a href="{{ route('event-participants.create') }}" class="btn btn-primary btn-sm">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Filtros y búsqueda --}}
                    <div class="card-body">
                        <form method="GET" action="{{ route('event-participants.index') }}" class="form-inline mb-3">
                            <input type="text" name="search" class="form-control mr-2" placeholder="Buscar por Observación" value="{{ request('search') }}">

                            <select name="status" class="form-control mr-2">
                                <option value="">-- Estado --</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>

                            <select name="event_id" class="form-control mr-2">
                                <option value="">-- Evento --</option>
                                @foreach($uniqueEventIds as $id)
                                    <option value="{{ $id }}" {{ request('event_id') == $id ? 'selected' : '' }}>{{ $id }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-secondary">Filtrar</button>
                        </form>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Event Id</th>
                                        <th>User Id</th>
                                        <th>Registration Date</th>
                                        <th>Observations</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eventParticipants as $eventParticipant)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $eventParticipant->event_id }}</td>
                                            <td>{{ $eventParticipant->user_id }}</td>
                                            <td>{{ $eventParticipant->registration_date }}</td>
                                            <td>{{ $eventParticipant->observations }}</td>
                                            <td>{{ $eventParticipant->status }}</td>
                                            <td>
                                                <form action="{{ route('event-participants.destroy', $eventParticipant->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('event-participants.show', $eventParticipant->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('event-participants.edit', $eventParticipant->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {!! $eventParticipants->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
