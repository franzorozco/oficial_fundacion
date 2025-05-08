@extends('adminlte::page')

@section('title', 'Deleted Users')

@section('content_header')
    <h1>Deleted Users</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">{{ __('Deleted Users') }}</span>
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                                <td>
                                    @if ($user->roles->isNotEmpty())
                                        {{ $user->roles->first()->name }}
                                    @else
                                        No Role
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fa fa-fw fa-undo"></i> Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('users.forceDelete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-fw fa-trash"></i> Permanently Delete
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

    {!! $users->links() !!}
@stop
