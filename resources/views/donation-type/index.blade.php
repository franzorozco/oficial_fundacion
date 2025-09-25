@extends('adminlte::page')

@section('title', 'Tipos de Donación')

@section('content_header')
    <h1>{{ __('Tipos de Donación') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            
                            <span id="card_title">
                                {{ __('Tipos de Donación') }}
                            </span>
                            
                            @can('donation-types.crear')
                            <div class="float-right">
                                <a href="{{ route('donation-types.create') }}" class="btn btn-outline-primary btn-sm float-right" data-placement="left" ">
                                    {{ __('Crear Nuevo') }}
                                </a>
                            </div>
                            @endcan
                        </div>
                        <div class="p-3">
                            <form action="{{ route('donation-types.index') }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control" placeholder="{{ __('Buscar tipo de donación') }}" value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit" ">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
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
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donationTypes as $donationType)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $donationType->name }}</td>
                                            <td>{{ $donationType->description }}</td>
                                            <td>
                                                <form action="{{ route('donation-types.destroy', $donationType->id) }}" method="POST">
                                                    @can('donation-types.ver')
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('donation-types.show', $donationType->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                                    </a>
                                                    @endcan
                                                    @can('donation-types.editar')
                                                    <a class="btn btn-sm btn-outline-success" href="{{ route('donation-types.edit', $donationType->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @endcan
                                                    @csrf
                                                    @can('donation-types.eliminar')
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de eliminar?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
                                                    </button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $donationTypes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@stop
