@extends('adminlte::page')

@section('title', 'Donations Cashes')

@section('content_header')
    <h1>{{ __('Donations Cashes') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header d-flex flex-wrap gap-2 align-items-center">
                            <span id="card_title" class="me-auto">
                                {{ __('Donations Cashes') }}
                            </span>

                        
                            <a href="{{ route('donations-cashes.create') }}" class="btn btn-primary btn-sm">
                                {{ __('Create New') }}
                            </a>
                        </div>
                        <form method="GET" action="{{ route('donations-cashes.index') }}" class="flex-grow-1">
                                <div class="input-group w-100">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        class="form-control" 
                                        placeholder="{{ __('Search by donor, method, campaign...') }}" 
                                        value="{{ request()->search }}"
                                    >
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
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
                                        <th>Donor Name</th> <!-- Aquí cambiaremos para mostrar el nombre -->
                                        <th>External Donor Name</th> <!-- Aquí mostramos el nombre del external donor -->
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Donation Date</th>
                                        <th>Campaign Name</th> <!-- Aquí mostramos el nombre de la campaña -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donationsCashes as $donationsCash)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $donationsCash->user ? $donationsCash->user->name : 'N/A' }}</td> <!-- Muestra el nombre del donante -->
                                            <td>{{ $donationsCash->external_donor ? $donationsCash->external_donor->names : 'N/A' }}</td> <!-- Muestra el nombre del external donor -->
                                            <td>{{ $donationsCash->amount }}</td>
                                            <td>{{ $donationsCash->method }}</td>
                                            <td>{{ $donationsCash->donation_date }}</td>
                                            <td>{{ $donationsCash->campaign ? $donationsCash->campaign->name : 'N/A' }}</td> <!-- Muestra el nombre de la campaña -->

                                            <td>
                                                <form action="{{ route('donations-cashes.destroy', $donationsCash->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('donations-cashes.show', $donationsCash->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('donations-cashes.edit', $donationsCash->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
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
                {!! $donationsCashes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
