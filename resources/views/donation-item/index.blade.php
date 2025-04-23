@extends('adminlte::page')

@section('title', __('Donation Items'))

@section('content_header')
    <h1>{{ __('Donation Items') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span id="card_title">{{ __('Donation Items') }}</span>
                <!-- Formulario de búsqueda estilizado -->
                <div class="card-body pt-3 pb-0">
                    <form method="GET" action="{{ route('donation-items.index') }}">
                        <div class="input-group mb-3">
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="{{ __('Search by item name, type, unit...') }}" 
                                value="{{ request()->search }}"
                            >
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i> {{ __('Search') }}
                            </button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('donation-items.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> {{ __('Create New') }}
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
                            <th>Donation Id</th>
                            <th>Donation Type Id</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donationItems as $donationItem)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $donationItem->donation_id }}</td>
                                <td>{{ $donationItem->donation_type_id }}</td>
                                <td>{{ $donationItem->item_name }}</td>
                                <td>{{ $donationItem->quantity }}</td>
                                <td>{{ $donationItem->unit }}</td>
                                <td>{{ $donationItem->description }}</td>
                                <td>
                                    <form action="{{ route('donation-items.destroy', $donationItem->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('donation-items.show', $donationItem->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('donation-items.edit', $donationItem->id) }}">
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

    {!! $donationItems->withQueryString()->links() !!}
@stop
