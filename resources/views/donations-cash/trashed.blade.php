@extends('adminlte::page')

@section('title', 'Donaciones Eliminadas')

@section('content_header')
    <h1>Donaciones Eliminadas</h1>
@endsection

@section('content')
<div class="container-fluid">

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('donations-cashes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Donaciones
            </a>
        </div>
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donante</th>
                            <th>Donante Externo</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Fecha</th>
                            <th>Campaña</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donationsCashes as $donationsCash)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $donationsCash->user->name ?? 'N/A' }}</td>
                                <td>{{ $donationsCash->external_donor->names ?? 'N/A' }}</td>
                                <td>{{ $donationsCash->amount }}</td>
                                <td>{{ $donationsCash->method }}</td>
                                <td>{{ $donationsCash->donation_date }}</td>
                                <td>{{ $donationsCash->campaign->name ?? 'N/A' }}</td>
                                <td class="d-flex gap-1">
                                    {{-- Restaurar --}}
                                    <form action="{{ route('donations-cashes.restore', $donationsCash->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('¿Restaurar esta donación?')">
                                            <i class="fa fa-undo"></i>
                                        </button>
                                    </form>

                                    {{-- Eliminar permanentemente --}}
                                    <form action="{{ route('donations-cashes.force-delete', $donationsCash->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar permanentemente esta donación?')">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {!! $donationsCashes->links() !!}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
