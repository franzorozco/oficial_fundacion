@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <span id="card_title" style="flex: 1 1 auto;">Usuarios</span>

                <form method="GET" action="{{ route('users.index') }}" id="filterForm" class="d-flex flex-wrap gap-2 align-items-center" style="flex: 3 1 600px;">
                    <!-- Barra de búsqueda -->
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuarios"
                        value="{{ request('search') }}" style="width: 250px; min-width: 180px;">

                    <button type="submit" class="btn btn-secondary btn-sm" style="white-space: nowrap;">
                        Filtrar
                    </button>

                    <!-- Botón para desplegar filtros extra -->
                    <button type="button" id="toggleFiltersBtn" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">
                        Más filtros &#x25BC;
                    </button>

                    <!-- Panel de filtros oculto -->
                    <div id="extraFilters" style="width: 100%; margin-top: 10px; display: none; gap: 10px; flex-wrap: wrap; border: 1px solid #ccc; padding: 10px; border-radius: 5px; background: #f9f9f9;">
                        <select name="role" class="form-select" style="width: 180px;">
                            <option value="">Todos los roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="email_domain" class="form-select" style="width: 180px;">
                            <option value="">Todos los dominios</option>
                            @foreach ($emailDomains as $domain)
                                <option value="{{ $domain }}" {{ request('email_domain') == $domain ? 'selected' : '' }}>
                                    {{ $domain }}
                                </option>
                            @endforeach
                        </select>
                        
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ request('start_date') }}" style="width: 220px;">
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ request('end_date') }}" style="width: 220px;">

                        <select name="city" class="form-select" style="width: 180px;">
                            <option value="">Todas las ciudades</option>
                            @foreach ($cityList as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>

                        <div class="form-group mb-2" style="min-width: 200px;">
                            <label for="login_activity" class="form-label">Actividad de inicio de sesión</label>
                            <input type="text" name="login_activity" class="form-control" id="login_activity" placeholder="Selecciona filtro" readonly>
                            <div class="mt-2 d-flex flex-wrap gap-1" id="login-options">
                                @php
                                    $loginOptions = [
                                        'top_10' => 'Top 10 más activos',
                                        'none' => 'Nunca han iniciado sesión',
                                    ];
                                @endphp
                                @foreach ($loginOptions as $key => $label)
                                    <button type="button" class="btn btn-outline-dark btn-sm login-btn" data-value="{{ $key }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="login_activity_value" id="login_activity_value" value="{{ request('login_activity_value') }}">
                    </div>
                </form>

                <div class="float-right" style="display: flex; gap: 5px; flex-wrap: wrap;">
                    @can('users.crear')
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">
                        Crear nuevo
                    </a>
                    @endcan
                    @can('users.verEliminados')
                    <a href="{{ route('users.trashed') }}" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">
                        Ver usuarios eliminados
                    </a>
                    @endcan
                    @can('users.regenerarPDF')
                    <a href="{{ route('users.pdf', request()->only([
                        'search', 'role', 'email_domain', 'start_date', 'end_date', 'city', 'login_activity_value'
                    ])) }}" class="btn btn-outline-success btn-sm" style="white-space: nowrap;">
                        Regenerar PDF
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead">
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Celular</th>
                            <th>Ciudad</th>
                            <th>N° de documento</th>
                            <th>Rol</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                                <td>{{ $user->profile->document_number ?? 'N/A' }}</td>
                                <td>
                                    @if ($user->roles->isNotEmpty())
                                        {{ $user->roles->pluck('name')->implode(', ') }}
                                    @else
                                        Sin rol
                                    @endif
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @can('users.ver')
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('users.show', $user->id) }}">
                                            <i class="fa fa-fw fa-eye"></i> Ver
                                        </a>
                                        @endcan
                                        @can('users.editar')
                                        <a class="btn btn-sm btn-outline-success" href="{{ route('users.edit', $user->id) }}">
                                            <i class="fa fa-fw fa-edit"></i> Editar
                                        </a>
                                        @endcan
                                        @can('users.editarRol')
                                        <a class="btn btn-sm btn-outline-warning" href="{{ route('users.editRol', $user->id) }}">
                                            <i class="fa fa-fw fa-user-tag"></i> Rol
                                        </a>
                                        @endcan
                                        @can('users.imprimir')
                                        <a class="btn btn-sm btn-outline-info" href="{{ route('users.printFullInfo', $user->id) }}" target="_blank">
                                            <i class="fa fa-fw fa-print"></i> Imprimir Info
                                        </a>
                                        @endcan

                                        @csrf
                                        @method('DELETE')
                                        @can('users.eliminar')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;">
                                            <i class="fa fa-fw fa-trash"></i> Eliminar
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
    {!! $users->withQueryString()->links() !!}
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginButtons = document.querySelectorAll('.login-btn');
        const loginInput = document.getElementById('login_activity');
        const hiddenInput = document.getElementById('login_activity_value');

        loginButtons.forEach(button => {
            button.addEventListener('click', function () {
                const value = this.getAttribute('data-value');
                const label = this.textContent;

                hiddenInput.value = value;
                loginInput.value = label;
            });
        });

        const toggleBtn = document.getElementById('toggleFiltersBtn');
        const extraFilters = document.getElementById('extraFilters');

        toggleBtn.addEventListener('click', () => {
            if (extraFilters.style.display === 'none' || extraFilters.style.display === '') {
                extraFilters.style.display = 'flex';
                toggleBtn.innerHTML = 'Menos filtros &#x25B2;';
            } else {
                extraFilters.style.display = 'none';
                toggleBtn.innerHTML = 'Más filtros &#x25BC;';
            }
        });
    });
</script>
@endsection
