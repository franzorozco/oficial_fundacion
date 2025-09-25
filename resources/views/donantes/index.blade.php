@extends('adminlte::page')

@section('title', 'Donantes')

@section('content_header')
    <h1>Donantes</h1>
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
                <span id="card_title" style="flex: 1 1 auto;">Donantes</span>
                @can('donantes.filtrar')
                <form method="GET" action="{{ route('donantes.index') }}" id="filterForm" class="d-flex flex-wrap gap-2 align-items-center" style="flex: 3 1 600px;">
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

                        <input type="datetime-local" name="start_date" class="form-control" value="{{ request('start_date') }}" style="width: 220px;">
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ request('end_date') }}" style="width: 220px;">

                        <select name="city" class="form-select" style="width: 180px;">
                            <option value="">Todas las ciudades</option>
                            @foreach ($cityList as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>

                        <div class="form-group mb-2" style="min-width: 200px;">
                            <label for="login_activity" class="form-label">Preferencias de filtros</label>
                            <div class="d-flex flex-wrap gap-1">
                                @php
                                    $loginOptions = [
                                        'top_donations' => 'Top 10 por donaciones',
                                        'top_items' => 'Top 10 por ítems donados',
                                        'active' => 'Activos',
                                        'inactive' => 'Inactivos',
                                    ];
                                    $selectedFilters = request()->input('login_activity_values', []);
                                    if (!is_array($selectedFilters)) {
                                        $selectedFilters = [$selectedFilters];
                                    }
                                @endphp

                                @foreach ($loginOptions as $key => $label)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="login_activity_values[]" value="{{ $key }}" id="filter_{{ $key }}"
                                            {{ in_array($key, $selectedFilters) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="filter_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </form>
                @endcan

                <div class="float-right" style="display: flex; gap: 5px; flex-wrap: wrap;">
                    @can('donantes.crear')
                    <a href="{{ route('donantes.create') }}" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">
                        Crear nuevo
                    </a>
                    @endcan
                    @can('donantes.ver_eliminados')
                    <a href="{{ route('donantes.trashed') }}" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">
                        Ver donantes eliminados
                    </a>
                    @endcan
                    @can('donantes.exportar_pdf')
                    <a href="{{ route('donantes.pdf', request()->only([
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
                            <th>Donaciones totales</th>
                            <th>Articulos donados</th>
                            <th>Fecha ultima donacion</th>
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
                                <td>{{ $user->donations_made_count ?? 0 }}</td>
                                <td>
                                    {{
                                        $user->donationsMade->flatMap->items->count() ?? 0
                                    }}
                                </td>

                                <td>
                                    {{
                                        optional($user->donationsMade->sortByDesc('created_at')->first())->created_at
                                            ? optional($user->donationsMade->sortByDesc('created_at')->first())->created_at->format('d/m/Y H:i')
                                            : 'N/A'
                                    }}
                                </td>

                                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('donantes.destroy', $user->id) }}" method="POST">
                                        @can('donantes.ver')
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('donantes.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                        @endcan
                                        @can('donantes.editar')
                                        <a class="btn btn-sm btn-outline-success" href="{{ route('donantes.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                        @endcan
                                        @csrf
                                        @method('DELETE')
                                        @can('donantes.eliminar')
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
        // Manejo de botones para actividad de login
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

        // Toggle del panel de filtros extra
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
