@extends('adminlte::page')

@section('title', 'External Donors')

@section('content_header')
    <h1>{{ __('External Donors') }}</h1>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('external-donors.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o correo" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="paternal" class="form-control" placeholder="Apellido paterno" value="{{ request('paternal') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="maternal" class="form-control" placeholder="Apellido materno" value="{{ request('maternal') }}">
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
                        <a href="{{ route('external-donors.index') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>Names</th>
                            <th>Paternal Surname</th>
                            <th>Maternal Surname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($externalDonors as $externalDonor)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $externalDonor->names }}</td>
                                <td>{{ $externalDonor->paternal_surname }}</td>
                                <td>{{ $externalDonor->maternal_surname }}</td>
                                <td>{{ $externalDonor->email }}</td>
                                <td>{{ $externalDonor->phone }}</td>
                                <td>{{ $externalDonor->address }}</td>

                                <td>
                                    <form action="{{ route('external-donors.destroy', $externalDonor->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('external-donors.show', $externalDonor->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('external-donors.edit', $externalDonor->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
    {!! $externalDonors->withQueryString()->links() !!}
@endsection
