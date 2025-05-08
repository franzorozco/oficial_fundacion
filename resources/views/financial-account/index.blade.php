@extends('adminlte::page')

@section('title', 'Financial Accounts')

@section('content_header')
    <h1>{{ __('Financial Accounts') }}</h1>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    {{ __('Financial Accounts') }}
                </span>

                <div class="float-right">
                    <a href="{{ route('financial-accounts.create') }}" class="btn btn-primary btn-sm" data-placement="left">
                        {{ __('Create New') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-white">
            <!-- Barra de búsqueda y filtros -->
            <form method="GET" action="{{ route('financial-accounts.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Name or Type">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="min_balance" value="{{ request('min_balance') }}" class="form-control" placeholder="Min Balance">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="max_balance" value="{{ request('max_balance') }}" class="form-control" placeholder="Max Balance">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Balance</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($financialAccounts as $financialAccount)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $financialAccount->name }}</td>
                                <td>{{ $financialAccount->type }}</td>
                                <td>{{ $financialAccount->balance }}</td>
                                <td>{{ $financialAccount->description }}</td>
                                <td>
                                    <form action="{{ route('financial-accounts.destroy', $financialAccount->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('financial-accounts.show', $financialAccount->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('financial-accounts.edit', $financialAccount->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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

    {!! $financialAccounts->withQueryString()->links() !!}
@endsection
