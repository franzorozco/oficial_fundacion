@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
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
                <span id="card_title">{{ __('Users') }}</span>
                
                <!-- Formulario de búsqueda -->
                <form method="GET" action="{{ route('users.index') }}" style="display: inline-block;">
                    <input type="text" name="search" class="form-control" placeholder="Search users" value="{{ request('search') }}" style="width: 200px; display: inline-block;">
                    <button class="btn btn-secondary btn-sm" type="submit">Search</button>
                </form>

                @can('users.create')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    {{ __('Create New') }}
                </a>
                @endcan
                <a href="{{ route('users.trashed') }}" class="btn btn-danger btn-sm">
                    {{ __('View Deleted Users') }}
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
                        <th>Document Number</th> <!-- Profile -->
                        <th>Location</th> <!-- Profile -->
                        <th>Skills</th> <!-- Profile -->
                        <th>Availability</th> <!-- Profile -->
                        <th>Role</th> <!-- Roles -->
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

                            <!-- Datos del profile (relación) -->
                            <td>{{ $user->profile->document_number ?? 'N/A' }}</td>
                            <td>{{ $user->profile->location ?? 'N/A' }}</td>
                            <td>{{ $user->profile->skills ?? 'N/A' }}</td>
                            <td>
                                @if($user->profile)
                                    {{ $user->profile->availability_days ?? '' }} - {{ $user->profile->availability_hours ?? '' }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <!-- Mostrar primer rol -->
                            <td>
                                @if ($user->roles->isNotEmpty())
                                    {{ $user->roles->first()->name }}
                                @else
                                    No Role
                                @endif
                            </td>

                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @can('users.view')
                                    <a class="btn btn-sm btn-primary" href="{{ route('users.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                    @endcan
                                    @can('users.edit')
                                    <a class="btn btn-sm btn-success" href="{{ route('users.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                    @endcan
                                    @can('users.editRol')
                                    <a class="btn btn-sm btn-warning" href="{{ route('users.editRol', $user->id) }}"><i class="fa fa-fw fa-user-tag"></i> Rol</a>
                                    @endcan


                                    @csrf
                                    @method('DELETE')
                                    @can('users.delete')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                        <i class="fa fa-fw fa-trash"></i> Delete
                                    </button>
                                    @endcan
                                    
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                </table>
            </div>
        </div>
    </div>

    {!! $users->withQueryString()->links() !!}  <!-- Paginación con parámetros de búsqueda -->
@stop
