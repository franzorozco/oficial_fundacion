@extends('adminlte::page')

@section('title', __('Donation Requests'))

@section('content_header')
    <h1>{{ __('Donation Requests') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Donation Requests') }}
                            </span>
                            <div class="card-body pt-0">
                                <form method="GET" action="{{ route('donation-requests.index') }}">
                                    <div class="input-group mb-3">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            class="form-control" 
                                            placeholder="{{ __('Search by notes, state, user id...') }}" 
                                            value="{{ request()->search }}"
                                        >
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fas fa-search"></i> {{ __('Search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="float-right">
                                <a href="{{ route('donation-requests.create') }}" class="btn btn-primary btn-sm">
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
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Applicant User</th> <!-- Modificado -->
                                        <th>User In Charge</th> <!-- Modificado -->
                                        <th>Donation</th>
                                        <th>Request Date</th>
                                        <th>Notes</th>
                                        <th>State</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donationRequests as $donationRequest)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ optional($donationRequest->user)->name ?? 'N/A' }}</td> <!-- Modificado -->
                                            <td>{{ optional($donationRequest->user)->name ?? 'N/A' }}</td> <!-- Modificado -->
                                            <td>{{ optional($donationRequest->donation)->id ?? 'N/A' }}</td> <!-- Modificado (si deseas el nombre de la donación) -->
                                            <td>{{ $donationRequest->request_date }}</td>
                                            <td>{{ $donationRequest->notes }}</td>
                                            <td>{{ $donationRequest->state }}</td>

                                            <td>
                                                <form action="{{ route('donation-requests.destroy', $donationRequest->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('donation-requests.show', $donationRequest->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('donation-requests.edit', $donationRequest->id) }}">
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
                {!! $donationRequests->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
