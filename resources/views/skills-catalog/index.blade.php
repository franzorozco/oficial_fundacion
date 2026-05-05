@extends('adminlte::page')

@section('title', 'Catálogo de Habilidades')

@section('content_header')
    <h1>Catálogo de Habilidades</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                
                <span id="card_title">
                    Catálogo de Habilidades
                </span>

                <div class="float-right d-flex gap-2">
                    <a href="{{ route('skills-catalogs.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-plus"></i> Crear Nuevo
                    </a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger m-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skillsCatalogs as $skillsCatalog)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $skillsCatalog->name }}</td>
                                <td>{{ $skillsCatalog->description }}</td>
                                <td>
                                    <form action="{{ route('skills-catalogs.destroy', $skillsCatalog->id) }}" method="POST">

                                        <a class="btn btn-sm btn-outline-primary"
                                           href="{{ route('skills-catalogs.show', $skillsCatalog->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> Ver
                                        </a>

                                        <a class="btn btn-sm btn-outline-success"
                                           href="{{ route('skills-catalogs.edit', $skillsCatalog->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> Editar
                                        </a>

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="event.preventDefault(); confirm('¿Seguro que deseas eliminar este registro?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> Eliminar
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

    {!! $skillsCatalogs->withQueryString()->links() !!}
@endsection