@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Voluntarios</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span id="card_title" class="flex-grow-1">Volunteers</span>

            <form method="GET" action="{{ route('volunteers.index') }}" id="filterForm" class="d-flex flex-wrap gap-2 align-items-center flex-grow-3" style="min-width: 600px;">
                <!-- Barra de búsqueda -->
                <input type="text" name="search" class="form-control" placeholder="Buscar usuarios"
                    value="{{ request('search') }}" style="min-width: 180px; max-width: 250px;">

                <button type="submit" class="btn btn-secondary btn-sm" style="white-space: nowrap;">
                    Filtrar
                </button>

                <button type="button" id="toggleFiltersBtn" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">
                    Más filtros &#x25BC;
                </button>

                <div id="extraFilters" class="mt-3 d-none p-3 border rounded bg-light gap-3 flex-wrap" style="display: flex; flex-wrap: wrap;">
                    <!-- Fecha inicio -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label for="start_date" class="form-label">Fecha inicio</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <!-- Fecha fin -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label for="end_date" class="form-label">Fecha fin</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <!-- Ciudad -->
                    <div class="form-group mb-2" style="min-width: 180px;">
                        <label for="city" class="form-label">Ciudad</label>
                        <select name="city" id="city" class="form-select">
                            <option value="">Todas las ciudades</option>
                            @foreach ($cityList as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtros de actividad -->
                    <div class="form-group mb-2" style="min-width: 250px;">
                        <label class="form-label">Actividad de Voluntarios</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $activityFilters = [
                                    'most_tasks' => 'Más tareas realizadas',
                                    'most_distributions' => 'Más distribuciones hechas',
                                    'with_deleted' => 'Incluir eliminados',
                                    'only_deleted' => 'Solo eliminados',
                                ];
                            @endphp
                            @foreach ($activityFilters as $key => $label)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" name="activity_filters[]" value="{{ $key }}"
                                        id="activity_{{ $key }}" {{ in_array($key, (array) request('activity_filters')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activity_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="login_activity_value" id="login_activity_value" value="{{ request('login_activity_value') }}">

                    <!-- Días disponibles (CHECKBOX) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Días disponibles</label>
                        @php
                            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                        @endphp
                        @foreach ($days as $day)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="availability_days[]" value="{{ $day }}" id="day_{{ $day }}"
                                    {{ in_array($day, (array) request('availability_days')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Habilidades (CHECKBOX) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Habilidades</label>
                        @foreach ($skillsList as $skill)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="skills[]" value="{{ $skill }}" id="skill_{{ $loop->index }}"
                                    {{ in_array($skill, (array) request('skills')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="skill_{{ $loop->index }}">{{ $skill }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Horas disponibles (CHECKBOX) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Horas disponibles</label>
                        @foreach ($availabilityHoursList as $hour)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="availability_hours[]" value="{{ $hour }}" id="hour_{{ $loop->index }}"
                                    {{ in_array($hour, (array) request('availability_hours')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="hour_{{ $loop->index }}">{{ $hour }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tareas preferidas (CHECKBOX) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Tareas preferidas</label>
                        @foreach ($preferredTasksList as $task)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="preferred_tasks_multi[]" value="{{ $task }}" id="task_{{ $loop->index }}"
                                    {{ in_array($task, (array) request('preferred_tasks_multi')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="task_{{ $loop->index }}">{{ $task }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Idiomas hablados (CHECKBOX) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Idiomas hablados</label>
                        @foreach ($languagesList as $lang)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[]" value="{{ $lang }}" id="lang_{{ $loop->index }}"
                                    {{ in_array($lang, (array) request('languages_spoken')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang_{{ $loop->index }}">{{ $lang }}</label>
                            </div>
                        @endforeach
                    </div>


                    <!-- Transporte disponible (single select) -->
                    <div class="form-group mb-2" style="min-width: 160px;">
                        <label for="transport_available" class="form-label">Transporte</label>
                        <select name="transport_available" id="transport_available" class="form-select">
                            <option value="">-- Selecciona --</option>
                            <option value="1" {{ request('transport_available') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ request('transport_available') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Condición física (checkboxes) -->
                    <div class="form-group mb-2" style="min-width: 220px;">
                        <label class="form-label d-block">Condición física</label>
                        @php $conditions = ['buena', 'moderada', 'limitada']; @endphp
                        @foreach ($conditions as $condition)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="physical_condition[]" id="condition_{{ $condition }}" value="{{ $condition }}" class="form-check-input"
                                    {{ in_array($condition, (array) request('physical_condition')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="condition_{{ $condition }}">{{ ucfirst($condition) }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Edad mínima -->
                    <div class="form-group mb-2" style="min-width: 160px;">
                        <label for="min_age" class="form-label">Edad mínima</label>
                        <input type="number" name="min_age" id="min_age" class="form-control" value="{{ request('min_age') }}" min="0" max="120" step="1">
                    </div>

                    <!-- Edad máxima -->
                    <div class="form-group mb-2" style="min-width: 160px;">
                        <label for="max_age" class="form-label">Edad máxima</label>
                        <input type="number" name="max_age" id="max_age" class="form-control" value="{{ request('max_age') }}" min="0" max="120" step="1">
                    </div>
                </div>
            </form>

            <div class="float-right d-flex gap-2 flex-wrap">
                @can('volunteers.crear')
                <a href="{{ route('volunteers.create') }}" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">
                    Crear nuevo
                </a>
                @endcan
                @can('volunteers.verEliminados')
                <a href="{{ route('volunteers.trashed') }}" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">
                    Ver volunteers eliminados
                </a>
                @endcan
                @can('volunteers.regenerarPDF')
                <a href="{{ route('volunteers.pdf', request()->only([
                    'search', 'role', 'email_domain', 'start_date', 'end_date', 'city', 'login_activity_value'
                ])) }}" class="btn btn-outline-success btn-sm" style="white-space: nowrap;">
                    Regenerar PDF
                </a>
                @endcan     
            </div>
        </div>

        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Celular</th>
                            <th>Ciudad</th>
                            <th>N° de documento</th>
                            <th>Fecha de registro</th>
                            <th>Tareas totales realizadas</th>
                            <th>Distribuciones realizadas</th>
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
                                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>{{ $user->tareas_realizadas ?? 0 }}</td>
                                <td>{{ $user->distribuciones_realizadas ?? 0 }}</td>
                                <td>
                                    <form action="{{ route('volunteers.destroy', $user->id) }}" method="POST">
                                        @can('volunteers.ver')
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('volunteers.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                        @endcan
                                        @can('volunteers.editar')
                                        <a class="btn btn-sm btn-outline-success" href="{{ route('volunteers.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                        @endcan
                                        @csrf
                                        @method('DELETE')
                                        @can('volunteers.eliminar')
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
        // Toggle del panel de filtros extra
        const toggleBtn = document.getElementById('toggleFiltersBtn');
        const extraFilters = document.getElementById('extraFilters');

        toggleBtn.addEventListener('click', () => {
            if (extraFilters.classList.contains('d-none')) {
                extraFilters.classList.remove('d-none');
                toggleBtn.innerHTML = 'Menos filtros &#x25B2;';
            } else {
                extraFilters.classList.add('d-none');
                toggleBtn.innerHTML = 'Más filtros &#x25BC;';
            }
        });
    });
</script>
@endsection
