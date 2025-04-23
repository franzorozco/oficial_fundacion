@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <h1>{{ __('Notifications') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span id="card_title">{{ __('Notifications') }}</span>
                <a href="{{ route('notifications.create') }}" class="btn btn-primary btn-sm" data-placement="left">
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
                            <th>User Id</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $notification->user_id }}</td>
                                <td>{{ $notification->message }}</td>
                                <td>{{ $notification->status }}</td>
                                <td>
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('notifications.show', $notification->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('notifications.edit', $notification->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                        </a>
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
    {!! $notifications->withQueryString()->links() !!}
@endsection
