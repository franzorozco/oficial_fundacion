@extends('adminlte::page')

@section('title', __('Campaign Finances'))

@section('content_header')
    <h1>{{ __('Campaign Finances') }}</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('campaign-finances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('Create New') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>{{ __('Campaign Id') }}</th>
                        <th>{{ __('Manager Id') }}</th>
                        <th>{{ __('Income') }}</th>
                        <th>{{ __('Expenses') }}</th>
                        <th>{{ __('Net Balance') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaignFinances as $campaignFinance)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $campaignFinance->campaign_id }}</td>
                            <td>{{ $campaignFinance->manager_id }}</td>
                            <td>{{ $campaignFinance->income }}</td>
                            <td>{{ $campaignFinance->expenses }}</td>
                            <td>{{ $campaignFinance->net_balance }}</td>
                            <td>
                                <a href="{{ route('campaign-finances.show', $campaignFinance->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('campaign-finances.edit', $campaignFinance->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('campaign-finances.destroy', $campaignFinance->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {!! $campaignFinances->withQueryString()->links() !!}
    </div>
@stop
