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
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Donations Cashes') }}
                            </span>
        <!-- Formulario de búsqueda estilizado -->
                            <div class="card-body pt-3 pb-0">
                                <form method="GET" action="{{ route('donations-cashes.index') }}">
                                    <div class="input-group mb-3">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            class="form-control" 
                                            placeholder="{{ __('Search by donor, method, campaign...') }}" 
                                            value="{{ request()->search }}"
                                        >
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fas fa-search"></i> {{ __('Search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="float-right">
                                <a href="{{ route('donations-cashes.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>Donor Id</th>
                                        <th>External Donor Id</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Donation Date</th>
                                        <th>Campaign Id</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donationsCashes as $donationsCash)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $donationsCash->donor_id }}</td>
                                            <td>{{ $donationsCash->external_donor_id }}</td>
                                            <td>{{ $donationsCash->amount }}</td>
                                            <td>{{ $donationsCash->method }}</td>
                                            <td>{{ $donationsCash->donation_date }}</td>
                                            <td>{{ $donationsCash->campaign_id }}</td>

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
