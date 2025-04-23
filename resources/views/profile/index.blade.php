@extends('adminlte::page')

@section('title', 'Profiles')

@section('content_header')
    <h1>{{ __('Profiles') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">{{ __('Profiles') }}</span>
                <div class="float-right">
                    <a href="{{ route('profiles.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                            <th>User Id</th>
                            <th>Bio</th>
                            <th>Document Number</th>
                            <th>Photo</th>
                            <th>Birthdate</th>
                            <th>Skills</th>
                            <th>Interests</th>
                            <th>Availability Days</th>
                            <th>Availability Hours</th>
                            <th>Location</th>
                            <th>Transport Available</th>
                            <th>Experience Level</th>
                            <th>Physical Condition</th>
                            <th>Preferred Tasks</th>
                            <th>Languages Spoken</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($profiles as $profile)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $profile->user_id }}</td>
                                <td>{{ $profile->bio }}</td>
                                <td>{{ $profile->document_number }}</td>
                                <td>{{ $profile->photo }}</td>
                                <td>{{ $profile->birthdate }}</td>
                                <td>{{ $profile->skills }}</td>
                                <td>{{ $profile->interests }}</td>
                                <td>{{ $profile->availability_days }}</td>
                                <td>{{ $profile->availability_hours }}</td>
                                <td>{{ $profile->location }}</td>
                                <td>{{ $profile->transport_available }}</td>
                                <td>{{ $profile->experience_level }}</td>
                                <td>{{ $profile->physical_condition }}</td>
                                <td>{{ $profile->preferred_tasks }}</td>
                                <td>{{ $profile->languages_spoken }}</td>
                                <td>
                                    <form action="{{ route('profiles.destroy', $profile->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('profiles.show', $profile->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('profiles.edit', $profile->id) }}">
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

    {!! $profiles->withQueryString()->links() !!}
@endsection
