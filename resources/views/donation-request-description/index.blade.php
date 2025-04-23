@extends('adminlte::page')

@section('title', 'Donation Request Descriptions')

@section('content_header')
    <h1>{{ __('Donation Request Descriptions') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Donation Request Descriptions') }}
                            </span>
                            <!-- Buscador estilizado -->
                            <div class="card-body pt-0">
                                <form method="GET" action="{{ route('donation-request-descriptions.index') }}">
                                    <div class="input-group mb-3">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            class="form-control" 
                                            placeholder="{{ __('Search by name, reason, address...') }}" 
                                            value="{{ request()->search }}"
                                        >
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fas fa-search"></i> {{ __('Search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                             <div class="float-right">
                                <a href="{{ route('donation-request-descriptions.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>Donation Request Id</th>
                                        <th>Recipient Name</th>
                                        <th>Recipient Address</th>
                                        <th>Recipient Contact</th>
                                        <th>Recipient Type</th>
                                        <th>Reason</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Extra Instructions</th>
                                        <th>Supporting Documents</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donationRequestDescriptions as $donationRequestDescription)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $donationRequestDescription->donation_request_id }}</td>
                                            <td>{{ $donationRequestDescription->recipient_name }}</td>
                                            <td>{{ $donationRequestDescription->recipient_address }}</td>
                                            <td>{{ $donationRequestDescription->recipient_contact }}</td>
                                            <td>{{ $donationRequestDescription->recipient_type }}</td>
                                            <td>{{ $donationRequestDescription->reason }}</td>
                                            <td>{{ $donationRequestDescription->latitude }}</td>
                                            <td>{{ $donationRequestDescription->longitude }}</td>
                                            <td>{{ $donationRequestDescription->extra_instructions }}</td>
                                            <td>{{ $donationRequestDescription->supporting_documents }}</td>
                                            <td>
                                                <form action="{{ route('donation-request-descriptions.destroy', $donationRequestDescription->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('donation-request-descriptions.show', $donationRequestDescription->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('donation-request-descriptions.edit', $donationRequestDescription->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $donationRequestDescriptions->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
